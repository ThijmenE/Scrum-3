import * as THREE from 'three';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
import { loadModelConfig, materialPresets } from './config.js';

// strip path/extension from a model filename so we can match it to a config
function normalizeName(name) {
  return name
    .split("/").pop()
    .replace(/^\d+_/, "")
    .replace(/\.(glb|gltf)$/i, "")
    .toLowerCase();
}

function getModelName() {
  if (typeof HUIDIG_3D_MODEL !== "string") return "default";
  return HUIDIG_3D_MODEL;
}

async function getActiveMaterialGroups() {
  const modelName = normalizeName(getModelName());
  return await loadModelConfig(modelName);
}

// --- scene setup ---

const scene = new THREE.Scene();
scene.background = null;

const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
camera.position.set(0, 1.5, 4);

const viewerElement = document.getElementById("viewer");

const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
renderer.setSize(viewerElement.clientWidth, viewerElement.clientHeight);
renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
renderer.setClearColor(0x000000, 0);
renderer.shadowMap.enabled = true;
renderer.outputColorSpace = THREE.SRGBColorSpace;
renderer.toneMapping = THREE.ACESFilmicToneMapping;
renderer.toneMappingExposure = 0.8;
viewerElement.appendChild(renderer.domElement);

// build a simple env map so materials catch some light
const pmremGenerator = new THREE.PMREMGenerator(renderer);
pmremGenerator.compileEquirectangularShader();

const envScene = new THREE.Scene();
envScene.background = new THREE.Color(0x888888);
const envFloor = new THREE.Mesh(
  new THREE.PlaneGeometry(10, 10),
  new THREE.MeshStandardMaterial({ color: 0xffffff, roughness: 0.6, metalness: 0.0 })
);
envFloor.rotation.x = -Math.PI / 2;
envFloor.position.y = -0.5;
envScene.add(envFloor);

const envLight = new THREE.DirectionalLight(0xffffff, 1.5);
envLight.position.set(0, 2, 1);
envScene.add(envLight);
const envLight2 = new THREE.DirectionalLight(0xffffff, 0.8);
envLight2.position.set(0, 1, -1);
envScene.add(envLight2);

scene.environment = pmremGenerator.fromScene(envScene, 0.04).texture;

const controls = new OrbitControls(camera, renderer.domElement);
controls.enableDamping = true;

// lights
scene.add(new THREE.AmbientLight(0xffffff, 1.5));

const keyLight = new THREE.DirectionalLight(0xffffff, 2.0);
keyLight.position.set(8, 6, 8);
scene.add(keyLight);

const fillLight = new THREE.DirectionalLight(0xffffff, 1.5);
fillLight.position.set(-6, 4, -6);
scene.add(fillLight);

const backLight = new THREE.DirectionalLight(0xffffff, 1.0);
backLight.position.set(0, 8, -10);
scene.add(backLight);

// --- state ---

const state = {
  model: null,
  materials: {},
  meshes: {},
  layers: {},
  activeMesh: {},
  selectedSwatches: {},
  autoRotate: false,
  dualState: {
    front: { shape: null, material: null },
    back:  { shape: null, material: null }
  },
  lampOn: { front: false, back: false },
  _defaults: null
};

// --- material helpers ---

function getMaterialPreset(groupName) {
  const preset = materialPresets[groupName] || materialPresets.default;
  return {
    metalness: preset.metalness ?? 0,
    roughness: preset.roughness ?? 0.7,
    envIntensity: preset.envIntensity ?? 1.0
  };
}

function applyMaterialPreset(material, groupName) {
  if (!material) return;
  const preset = getMaterialPreset(groupName);
  if ("metalness" in material) material.metalness = preset.metalness;
  if ("roughness" in material) material.roughness = preset.roughness;
  if ("envMapIntensity" in material) material.envMapIntensity = preset.envIntensity;
  material.needsUpdate = true;
}

function setMaterialColor(material, color, groupName, mesh) {
  if (!material) return;
  if (groupName === "pattern") {
    const cloned = material.clone();
    cloned.color.set(color);
    applyMaterialPreset(cloned, groupName);
    if (cloned.map) cloned.map = null;
    cloned.needsUpdate = true;
    if (mesh) {
      if (Array.isArray(mesh.material)) {
        const idx = mesh.material.indexOf(material);
        if (idx >= 0) mesh.material[idx] = cloned;
      } else {
        mesh.material = cloned;
      }
    }
    return;
  }
  material.color.set(color);
  applyMaterialPreset(material, groupName);
  if (material.map) material.map = null;
  material.needsUpdate = true;
}

// --- mesh visibility ---

function toggleMesh(groupName, meshName, isVisible) {
  if (!state.meshes[groupName]?.[meshName]) return;
  state.meshes[groupName][meshName].forEach(mesh => {
    mesh.visible = isVisible;
  });
  if (!state.layers[groupName]) state.layers[groupName] = {};
  state.layers[groupName][meshName] = isVisible;
}

function setActiveMesh(groupName, meshName, materialGroups) {
  const group = materialGroups[groupName];
  if (!group?.toggle) return;

  state.activeMesh[groupName] = meshName;

  // hide all meshes in this group first
  Object.keys(state.meshes[groupName] || {}).forEach(m => toggleMesh(groupName, m, false));

  if (meshName === "None") {
    document.querySelectorAll(`.mesh-toggle-btn[data-group="${groupName}"]`).forEach(btn => {
      btn.classList.toggle("is-active", btn.dataset.mesh === "None");
    });
    return;
  }

  const meshesToShow = group.meshOptionGroups?.[meshName] || [meshName];
  meshesToShow.forEach(m => {
    if (state.meshes[groupName][m]) toggleMesh(groupName, m, true);
  });

  document.querySelectorAll(`.mesh-toggle-btn[data-group="${groupName}"]`).forEach(btn => {
    btn.classList.toggle("is-active", btn.dataset.mesh === meshName);
  });
}

function applyColorToActiveMesh(groupName, color) {
  const activeMeshName = state.activeMesh[groupName];
  if (!activeMeshName || activeMeshName === "None") return;

  const group = state._lastMaterialGroups?.[groupName];
  const meshesToColor = group?.meshOptionGroups?.[activeMeshName] || [activeMeshName];

  meshesToColor.forEach(meshName => {
    state.meshes[groupName]?.[meshName]?.forEach(mesh => {
      if (!mesh.material) return;
      const mats = Array.isArray(mesh.material) ? mesh.material : [mesh.material];
      const newMats = mats.map(mat => {
        const cloned = mat.clone();
        cloned.color.set(color);
        applyMaterialPreset(cloned, groupName);
        if (cloned.map) cloned.map = null;
        cloned.needsUpdate = true;
        return cloned;
      });
      mesh.material = Array.isArray(mesh.material) ? newMats : newMats[0];
    });
  });
}

// --- swatch logic ---

function applySwatch(groupName, swatch, btn, materialGroups) {
  const group = materialGroups[groupName];
  if (!group) return;

  document.querySelectorAll(`.swatch-btn[data-group="${groupName}"]`).forEach(b => {
    b.classList.remove("is-active");
  });

  if (btn) {
    btn.classList.add("is-active");
    btn.setAttribute("data-group", groupName);
  }

  if (swatch.colors && typeof swatch.colors === 'object') {
    // multi-color swatch, each mesh gets its own color
    Object.entries(swatch.colors).forEach(([meshName, color]) => {
      state.meshes[groupName]?.[meshName]?.forEach(mesh => {
        if (!mesh.material) return;
        const mats = Array.isArray(mesh.material) ? mesh.material : [mesh.material];
        const newMats = mats.map(mat => {
          const cloned = mat.clone();
          cloned.color.set(color);
          applyMaterialPreset(cloned, groupName);
          if (cloned.map) cloned.map = null;
          cloned.needsUpdate = true;
          return cloned;
        });
        mesh.material = Array.isArray(mesh.material) ? newMats : newMats[0];
      });
    });
  } else if (swatch.color) {
    if (group.toggle) {
      applyColorToActiveMesh(groupName, swatch.color);
    } else {
      Object.values(state.meshes[groupName] || {}).flat().forEach(mesh => {
        if (!mesh.material) return;
        const mats = Array.isArray(mesh.material) ? mesh.material : [mesh.material];
        const newMats = mats.map(mat => {
          const cloned = mat.clone();
          cloned.color.set(swatch.color);
          applyMaterialPreset(cloned, groupName);
          if (cloned.map) cloned.map = null;
          cloned.needsUpdate = true;
          return cloned;
        });
        mesh.material = Array.isArray(mesh.material) ? newMats : newMats[0];
      });
    }
  }

  state.selectedSwatches[groupName] = swatch;
}

function getLampMeshName(side, shape) {
  return `${shape} lamp ${side}`.toLowerCase();
}

function applyLampVisibility(side) {
  const bucketKey = "_dual_" + side;
  const bucket = state.meshes[bucketKey] || {};
  // hide all lamp meshes for this side first
  Object.entries(bucket).forEach(([key, meshList]) => {
    if (key.includes(" lamp ")) meshList.forEach(m => { m.visible = false; });
  });
  if (!state.lampOn[side]) return;
  // show lamp for active shape only
  const shape = state.dualState[side].shape;
  if (!shape) return;
  const key = getLampMeshName(side, shape);
  bucket[key]?.forEach(m => { m.visible = true; });
}

// --- dual selector (front/back shape + material picker) ---

function getDualMeshName(side, shape, material, config) {
  // shapes in noMaterial list don't have a material in their mesh name
  if (config?.noMaterial?.map(s => s.toLowerCase()).includes(shape.toLowerCase())) {
    return `${shape} ${side}`.toLowerCase();
  }
  return `${shape} ${material} ${side}`.toLowerCase();
}

// hide all meshes that match the pattern "{shape} {material} {side}" for a given side
function hideAllDualMeshes(side, config) {
  config.shapes.forEach(shape => {
    if (config.noMaterial?.map(s => s.toLowerCase()).includes(shape.toLowerCase())) {
      const name = getDualMeshName(side, shape, null, config);
      const meshList = state.meshes["_dual_" + side]?.[name];
      if (meshList) meshList.forEach(m => { m.visible = false; });
    } else {
      config.materials.forEach(material => {
        const name = getDualMeshName(side, shape, material, config);
        const meshList = state.meshes["_dual_" + side]?.[name];
        if (meshList) meshList.forEach(m => { m.visible = false; });
      });
    }
  });
}

function applyDualSelection(side, config) {
  const ds = state.dualState[side];
  if (!ds.shape) return;

  hideAllDualMeshes(side, config);

  const targetName = getDualMeshName(side, ds.shape, ds.material, config);
  const meshList = state.meshes["_dual_" + side]?.[targetName];
  if (meshList) meshList.forEach(m => { m.visible = true; });
}

function buildDualSelectorUI(groupName, config) {
  const section = document.createElement("section");
  section.className = "group dual-selector-group";
  section.setAttribute("data-dual-side", config.side);

  const title = document.createElement("h2");
  title.className = "group-title";
  title.textContent = config.title;
  section.appendChild(title);

  function makeChipBtn(value, type, isActive) {
    const btn = document.createElement("button");
    btn.className = "dual-btn" + (isActive ? " is-active" : "");
    btn.setAttribute("data-dual-group", groupName);
    btn.setAttribute("data-dual-type", type);
    btn.setAttribute("data-value", value);
    btn.innerHTML = `
      <span class="dual-chip"></span>
      <span class="dual-label">${value}</span>
    `;
    return btn;
  }

  // shape picker
  const shapeLabel = document.createElement("p");
  shapeLabel.className = "group-subtitle";
  shapeLabel.textContent = "Shape";
  section.appendChild(shapeLabel);

  const shapeList = document.createElement("div");
  shapeList.className = "dual-list";

  config.shapes.forEach(shape => {
    const btn = makeChipBtn(shape, "shape", state.dualState[config.side].shape === shape);
    btn.onclick = () => {
      state.dualState[config.side].shape = shape;
      applyDualSelection(config.side, config);
      applyLampVisibility(config.side);
      section.querySelectorAll('[data-dual-type="shape"]').forEach(b => {
        b.classList.toggle("is-active", b.dataset.value === shape);
      });
      const isNoMaterial = config.noMaterial?.map(s => s.toLowerCase()).includes(shape.toLowerCase());
      const matSection = section.querySelector('.dual-material-section');
      if (matSection) matSection.style.display = isNoMaterial ? 'none' : '';
    };
    shapeList.appendChild(btn);
  });

  section.appendChild(shapeList);

  // material picker
  const matSection = document.createElement("div");
  matSection.className = "dual-material-section";

  const matLabel = document.createElement("p");
  matLabel.className = "group-subtitle";
  matLabel.style.marginTop = "12px";
  matLabel.textContent = "Material";
  matSection.appendChild(matLabel);

  const matList = document.createElement("div");
  matList.className = "dual-list";

  config.materials.forEach(material => {
    const btn = makeChipBtn(material, "material", state.dualState[config.side].material === material);
    btn.onclick = () => {
      state.dualState[config.side].material = material;
      applyDualSelection(config.side, config);
      section.querySelectorAll('[data-dual-type="material"]').forEach(b => {
        b.classList.toggle("is-active", b.dataset.value === material);
      });
      section._refreshColors?.(material);
    };
    matList.appendChild(btn);
  });

  if (!state.dualColor) state.dualColor = { front: null, back: null };
  matSection.appendChild(matList);
  section.appendChild(matSection);

  // color picker
  const colorSection = document.createElement("div");
  colorSection.className = "dual-color-section";
  colorSection.style.display = "none";

  const colorLabel = document.createElement("p");
  colorLabel.className = "group-subtitle";
  colorLabel.style.marginTop = "12px";
  colorLabel.textContent = "Color";
  colorSection.appendChild(colorLabel);

  const colorList = document.createElement("div");
  colorList.className = "swatch-list";
  colorSection.appendChild(colorList);
  section.appendChild(colorSection);

  // lamp toggle
  if (config.hasLamp) {
    const lampLabel = document.createElement("p");
    lampLabel.className = "group-subtitle";
    lampLabel.style.marginTop = "14px";
    lampLabel.textContent = "Lamp";

    const lampList = document.createElement("div");
    lampList.className = "dual-list";

    const lampBtn = makeChipBtn("Lamp", "lamp", false);
    lampBtn.setAttribute("data-lamp-side", config.side);
    lampBtn.onclick = () => {
      state.lampOn[config.side] = !state.lampOn[config.side];
      lampBtn.classList.toggle("is-active", state.lampOn[config.side]);
      applyLampVisibility(config.side);
    };

    lampList.appendChild(lampBtn);
    section.appendChild(lampLabel);
    section.appendChild(lampList);
  }

  section._refreshColors = (material) => {
    const swatches = config.materialColors?.[material];
    colorList.innerHTML = "";
    if (!swatches?.length) {
      colorSection.style.display = "none";
      return;
    }
    colorSection.style.display = "";
    swatches.forEach(swatch => {
      const btn = document.createElement("button");
      btn.className = "swatch-btn";
      btn.innerHTML = `
        <span class="swatch-chip" style="background:${swatch.color}"></span>
        <span class="swatch-label">${swatch.name}</span>
      `;
      btn.onclick = () => {
        state.dualColor[config.side] = swatch.color;
        const ds = state.dualState[config.side];
        const key = getDualMeshName(config.side, ds.shape, ds.material, config);
        const meshList = state.meshes["_dual_" + config.side]?.[key];
        if (meshList) {
          meshList.forEach(m => {
            if (!m.material) return;
            const mats = Array.isArray(m.material) ? m.material : [m.material];
            const newMats = mats.map(mat => {
              const cloned = mat.clone();
              cloned.color.set(swatch.color);
              cloned.needsUpdate = true;
              return cloned;
            });
            m.material = Array.isArray(m.material) ? newMats : newMats[0];
          });
        }
        colorList.querySelectorAll(".swatch-btn").forEach(b => b.classList.remove("is-active"));
        btn.classList.add("is-active");
      };
      colorList.appendChild(btn);
    });
  };

  return section;
}

// --- UI builder ---

function buildGroupUI(materialGroups) {
  const container = document.getElementById("groups");
  container.innerHTML = "";

  // collect dual selectors to render as a tabbed unit
  const dualGroups = Object.entries(materialGroups).filter(([, g]) => g.type === "dualSelector");
  let dualTabsRendered = false;

  Object.entries(materialGroups).forEach(([groupName, group]) => {
    if (group.type === "dualSelector") {
      if (dualTabsRendered) return; // only render the tab container once
      dualTabsRendered = true;

      // build the tab wrapper
      const tabWrapper = document.createElement("div");
      tabWrapper.className = "dual-tab-wrapper";

      // tab bar
      const tabBar = document.createElement("div");
      tabBar.className = "dual-tab-bar";

      const panels = [];

      dualGroups.forEach(([dualGroupName, dualConfig], i) => {
        const tabBtn = document.createElement("button");
        tabBtn.className = "dual-tab-btn" + (i === 0 ? " is-active" : "");
        tabBtn.textContent = dualConfig.title;
        tabBtn.onclick = () => {
          tabBar.querySelectorAll(".dual-tab-btn").forEach(b => b.classList.remove("is-active"));
          tabBtn.classList.add("is-active");
          panels.forEach(p => p.style.display = "none");
          panels[i].style.display = "";
        };
        tabBar.appendChild(tabBtn);

        const panel = buildDualSelectorUI(dualGroupName, dualConfig);
        panel.style.display = i === 0 ? "" : "none";
        panels.push(panel);
      });

      tabWrapper.appendChild(tabBar);
      dualGroups.forEach((_, i) => tabWrapper.appendChild(panels[i]));
      container.appendChild(tabWrapper);
      return;
    }

    const section = document.createElement("section");
    section.className = "group";

    const title = document.createElement("h2");
    title.className = "group-title";
    title.textContent = group.title;
    section.appendChild(title);

    if (group.subtitle) {
      const subtitle = document.createElement("p");
      subtitle.textContent = group.subtitle;
      subtitle.className = "group-subtitle";
      section.appendChild(subtitle);
    }

    // toggle buttons (e.g. Drip / Swirl / None)
    if (group.toggle && group.meshOptions) {
      const toggleWrap = document.createElement("div");
      toggleWrap.className = "toggle-group";

      group.meshOptions.forEach(meshName => {
        const btn = document.createElement("button");
        btn.className = "mesh-toggle-btn";
        btn.setAttribute("data-group", groupName);
        btn.setAttribute("data-mesh", meshName);
        btn.innerHTML = `
          <span class="swatch-chip"></span>
          <span class="swatch-label">${meshName}</span>
        `;
        btn.onclick = () => setActiveMesh(groupName, meshName, materialGroups);

        if (state.activeMesh[groupName] === meshName) {
          btn.classList.add("is-active");
        }

        toggleWrap.appendChild(btn);
      });

      section.appendChild(toggleWrap);
    }

    // color swatches
    if (group.swatches?.length > 0) {
      const swatchWrap = document.createElement("div");
      swatchWrap.className = "swatch-list";

      group.swatches.forEach(swatch => {
        const btn = document.createElement("button");
        btn.className = "swatch-btn";
        btn.setAttribute("data-group", groupName);
        btn.innerHTML = `
          <span class="swatch-chip" style="background:${swatch.color}"></span>
          <span class="swatch-label">${swatch.name}</span>
        `;
        btn.onclick = () => applySwatch(groupName, swatch, btn, materialGroups);
        swatchWrap.appendChild(btn);
      });

      section.appendChild(swatchWrap);
    }

    container.appendChild(section);
  });
}

// --- resize ---

function onWindowResize() {
  const rect = viewerElement.getBoundingClientRect();
  camera.aspect = rect.width / rect.height;
  camera.updateProjectionMatrix();
  renderer.setSize(rect.width, rect.height);
}

onWindowResize();
window.addEventListener("resize", onWindowResize);

// --- loader + init ---

const loader = new GLTFLoader();

function fitCameraToModel(model) {
  const box = new THREE.Box3().setFromObject(model);
  const size = box.getSize(new THREE.Vector3());
  const center = box.getCenter(new THREE.Vector3());

  const maxDim = Math.max(size.x, size.y, size.z);
  const fov = camera.fov * (Math.PI / 180);
  const distance = (maxDim / 2) / Math.tan(fov / 2) * 1.6;

  camera.position.set(center.x, center.y + size.y * 0.1, center.z + distance);
  camera.lookAt(center);
  controls.target.copy(center);
  controls.update();
}

async function init() {
  const loading = document.getElementById("loading");

  try {
    const materialGroups = await getActiveMaterialGroups();

    const model = await new Promise((resolve, reject) => {
      loader.load(
        HUIDIG_3D_MODEL,
        resolve,
        (xhr) => {
          if (loading && xhr.total) {
            const pct = Math.round((xhr.loaded / xhr.total) * 100);
            loading.textContent = `Loading 3D Model... ${pct}%`;
          }
        },
        reject
      );
    });

    state.model = model.scene;

    // set up empty buckets for each group
    Object.keys(materialGroups).forEach(groupName => {
      const group = materialGroups[groupName];
      if (group.type === "dualSelector") {
        state.meshes["_dual_" + group.side] = {};
        state.dualState[group.side].shape    = (group.defaultShape    || group.shapes[0])?.toLowerCase();
        state.dualState[group.side].material = (group.defaultMaterial || group.materials[0])?.toLowerCase();
      } else {
        state.meshes[groupName] = {};
        state.layers[groupName] = {};
      }
    });

    // walk the model and sort meshes + materials into their groups
    state.model.traverse(child => {
      if (!child.isMesh) return;

      if (Array.isArray(child.material)) {
        child.material.forEach((mat, i) => { state.materials[child.name + i] = mat; });
      } else {
        state.materials[child.name] = child.material;
      }

      Object.entries(materialGroups).forEach(([groupName, group]) => {
        const cleanName = child.name
          .replace(/\s-\sMaterial(?:\s\d+)?$/i, "")
          .replace(/_/g, " ")
          .trim();

        if (group.type === "dualSelector") {
          const bucketKey = "_dual_" + group.side;
          const matchesSide = cleanName.toLowerCase().endsWith(" " + group.side.toLowerCase());
          if (matchesSide) {
            const key = cleanName.toLowerCase();
            if (!state.meshes[bucketKey][key]) state.meshes[bucketKey][key] = [];
            state.meshes[bucketKey][key].push(child);
            child.visible = false;
          }
          return;
        }

        const rawName = child.name.replace(/\s-\sMaterial(?:\s\d+)?$/i, "").trim();
        group.meshNames.forEach(meshName => {
          if (cleanName.toLowerCase() === meshName.toLowerCase() ||
              rawName.toLowerCase() === meshName.toLowerCase()) {
            if (!state.meshes[groupName][meshName]) state.meshes[groupName][meshName] = [];
            state.meshes[groupName][meshName].push(child);
          }
        });
      });
    });

    // build target lists and apply presets (skip dual selectors)
    Object.entries(materialGroups).forEach(([groupName, group]) => {
      if (group.type === "dualSelector") return;

      group.targets = Object.entries(state.materials)
        .filter(([name]) => {
          const cleanName = name.replace(/\s-\sMaterial(?:\s\d+)?$/i, "").trim();
          return group.meshNames.some(m => {
            const cn = cleanName.toLowerCase();
            const mn = m.toLowerCase();
            return cn === mn || (cn.startsWith(mn) && /^\d+$/.test(cn.slice(mn.length)));
          });
        })
        .map(([name, material]) => ({ name, material }));

      group.targets.forEach(({ material }) => applyMaterialPreset(material, groupName));
    });

    // set default visibility for toggle groups (skip dual selectors)
    Object.entries(materialGroups).forEach(([groupName, group]) => {
      if (group.type === "dualSelector") return;
      if (!group.toggle) return;

      Object.keys(state.meshes[groupName] || {}).forEach(meshName => {
        toggleMesh(groupName, meshName, false);
      });

      const defaultMesh = group.defaultMesh || group.meshOptions?.[0];

      if (defaultMesh && defaultMesh !== "None") {
        state.activeMesh[groupName] = defaultMesh;
        const meshesToShow = group.meshOptionGroups?.[defaultMesh] || [defaultMesh];
        meshesToShow.forEach(m => {
          if (state.meshes[groupName]?.[m]) toggleMesh(groupName, m, true);
        });
      } else {
        state.activeMesh[groupName] = "None";
      }
    });

    buildGroupUI(materialGroups);
    state._lastMaterialGroups = materialGroups;

    // apply default dual selections
    Object.entries(materialGroups).forEach(([groupName, group]) => {
      if (group.type !== "dualSelector") return;
      applyDualSelection(group.side, group);
      const section = document.querySelector(`[data-dual-side="${group.side}"]`);
      if (!section?._refreshColors) return;
      const defaultMaterial = group.defaultMaterial?.toLowerCase() ?? '';
      section._refreshColors(defaultMaterial);
      if (group.defaultColor) {
        const swatches = group.materialColors?.[defaultMaterial] || [];
        const idx = swatches.findIndex(s => s.color === group.defaultColor);
        if (idx >= 0) {
          const btn = section.querySelectorAll('.dual-color-section .swatch-btn')[idx];
          if (btn) btn.click();
        }
      }
    });

    scene.add(state.model);
    fitCameraToModel(state.model);
    onWindowResize();

    // apply default swatches/colors
    Object.entries(materialGroups).forEach(([groupName, group]) => {
      if (group.type === "dualSelector") return;
      if (!group.swatches?.length) return;

      if (group.defaultSwatch) {
        const swatch = group.swatches.find(s => s.name === group.defaultSwatch);
        if (!swatch?.colors) return;
        const idx = group.swatches.findIndex(s => s.name === group.defaultSwatch);
        const btn = document.querySelectorAll(`.swatch-btn[data-group="${groupName}"]`)[idx];
        if (btn) applySwatch(groupName, swatch, btn, materialGroups);
        return;
      }

      if (!group.defaultColor) return;
      const idx = group.swatches.findIndex(s => s.color === group.defaultColor);
      if (idx < 0) return;
      const btn = document.querySelectorAll(`.swatch-btn[data-group="${groupName}"]`)[idx];
      if (btn) applySwatch(groupName, group.swatches[idx], btn, materialGroups);
    });

    // snapshot defaults for reset
    state._defaults = {
      activeMesh: { ...state.activeMesh },
      selectedSwatches: { ...state.selectedSwatches },
      dualState: JSON.parse(JSON.stringify(state.dualState)),
      lampOn: { ...state.lampOn }
    };

    // restore from URL if there's a saved design
    const savedState = new URLSearchParams(window.location.search).get('v');
    if (savedState) {
      const decoded = decodeState(savedState);
      if (decoded) applyState(decoded);
    }

  } catch (err) {
    console.error("BagEdit load error:", err);
  } finally {
    if (loading) loading.style.display = "none";
  }
}

init();

// --- url state sharing ---

function encodeState() {
  const stateData = { model: getModelName(), groups: {}, dual: {} };
  const materialGroups = state._lastMaterialGroups || {};

  Object.entries(state.selectedSwatches).forEach(([groupName, swatch]) => {
    if (!swatch) return;
    const group = materialGroups[groupName];
    if (!group) return;
    const idx = group.swatches.findIndex(s =>
      swatch.colors ? s.name === swatch.name : s.color === swatch.color
    );
    if (idx >= 0) stateData.groups[groupName] = { swatchIdx: idx };
  });

  Object.entries(state.activeMesh).forEach(([groupName, meshName]) => {
    if (!stateData.groups[groupName]) stateData.groups[groupName] = {};
    stateData.groups[groupName].mesh = meshName;
  });

  // encode dual selector state
  Object.entries(state.dualState).forEach(([side, ds]) => {
    stateData.dual[side] = { shape: ds.shape, material: ds.material };
  });
  stateData.lampOn = { ...state.lampOn };

  return btoa(unescape(encodeURIComponent(JSON.stringify(stateData))));
}

function decodeState(encoded) {
  try {
    return JSON.parse(decodeURIComponent(escape(atob(encoded))));
  } catch {
    return null;
  }
}

function applyState(stateData) {
  if (!stateData?.groups) return;
  const materialGroups = state._lastMaterialGroups;
  if (!materialGroups) return;

  Object.entries(stateData.groups).forEach(([groupName, groupState]) => {
    const group = materialGroups[groupName];
    if (!group) return;

    if (groupState.mesh && group.toggle) {
      setActiveMesh(groupName, groupState.mesh, materialGroups);
    }

    if (groupState.swatchIdx !== undefined && group.swatches[groupState.swatchIdx]) {
      const swatch = group.swatches[groupState.swatchIdx];
      const btn = document.querySelectorAll(`.swatch-btn[data-group="${groupName}"]`)[groupState.swatchIdx];
      if (btn) applySwatch(groupName, swatch, btn, materialGroups);
    }
  });

  // restore dual selector state
  if (stateData.dual) {
    Object.entries(stateData.dual).forEach(([side, ds]) => {
      if (!ds) return;
      state.dualState[side].shape = ds.shape;
      state.dualState[side].material = ds.material;
      const dualGroup = Object.values(materialGroups).find(g => g.type === "dualSelector" && g.side === side);
      if (dualGroup) {
        applyDualSelection(side, dualGroup);
        const section = document.querySelector(`[data-dual-side="${side}"]`);
        if (section) {
          section.querySelectorAll('[data-dual-type="shape"]').forEach(b =>
            b.classList.toggle("is-active", b.dataset.value === ds.shape)
          );
          section.querySelectorAll('[data-dual-type="material"]').forEach(b =>
            b.classList.toggle("is-active", b.dataset.value === ds.material)
          );
          section._refreshColors?.(ds.material);
        }
      }
    });
  }

  if (stateData.lampOn) {
    Object.entries(stateData.lampOn).forEach(([side, on]) => {
      state.lampOn[side] = on;
      applyLampVisibility(side);
      const lampBtn = document.querySelector(`[data-lamp-side="${side}"]`);
      if (lampBtn) lampBtn.classList.toggle("is-active", on);
    });
  }
}

function resetDesign() {
  if (!state._defaults || !state._lastMaterialGroups) return;
  const materialGroups = state._lastMaterialGroups;

  // re-run init defaults without reloading the model
  Object.entries(materialGroups).forEach(([groupName, group]) => {
    if (group.type === "dualSelector") return;
    if (!group.toggle) return;
    Object.keys(state.meshes[groupName] || {}).forEach(m => toggleMesh(groupName, m, false));
    const defaultMesh = group.defaultMesh || group.meshOptions?.[0];
    if (defaultMesh && defaultMesh !== "None" && state.meshes[groupName]?.[defaultMesh]) {
      state.activeMesh[groupName] = defaultMesh;
      toggleMesh(groupName, defaultMesh, true);
    } else {
      state.activeMesh[groupName] = "None";
    }
    document.querySelectorAll(`.mesh-toggle-btn[data-group="${groupName}"]`).forEach(btn => {
      btn.classList.toggle("is-active", btn.dataset.mesh === state.activeMesh[groupName]);
    });
  });

  state.selectedSwatches = {};
  document.querySelectorAll(".swatch-btn.is-active").forEach(b => b.classList.remove("is-active"));

  Object.entries(materialGroups).forEach(([groupName, group]) => {
    if (group.type === "dualSelector") return;
    if (!group.swatches?.length) return;
    if (group.defaultSwatch) {
      const swatch = group.swatches.find(s => s.name === group.defaultSwatch);
      if (!swatch?.colors) return;
      const idx = group.swatches.findIndex(s => s.name === group.defaultSwatch);
      const btn = document.querySelectorAll(`.swatch-btn[data-group="${groupName}"]`)[idx];
      if (btn) applySwatch(groupName, swatch, btn, materialGroups);
      return;
    }
    if (!group.defaultColor) return;
    const idx = group.swatches.findIndex(s => s.color === group.defaultColor);
    if (idx < 0) return;
    const btn = document.querySelectorAll(`.swatch-btn[data-group="${groupName}"]`)[idx];
    if (btn) applySwatch(groupName, group.swatches[idx], btn, materialGroups);
  });

  // reset dual selectors
  Object.entries(materialGroups).forEach(([groupName, group]) => {
    if (group.type !== "dualSelector") return;
    state.dualState[group.side].shape    = (group.defaultShape    || group.shapes[0])?.toLowerCase();
    state.dualState[group.side].material = (group.defaultMaterial || group.materials[0])?.toLowerCase();
    state.lampOn[group.side] = false;
    applyDualSelection(group.side, group);
    applyLampVisibility(group.side);
    const section = document.querySelector(`[data-dual-side="${group.side}"]`);
    if (section) {
      const ds = state.dualState[group.side];
      section.querySelectorAll('[data-dual-type="shape"]').forEach(b =>
        b.classList.toggle("is-active", b.dataset.value === ds.shape)
      );
      section.querySelectorAll('[data-dual-type="material"]').forEach(b =>
        b.classList.toggle("is-active", b.dataset.value === ds.material)
      );
      section._refreshColors?.(ds.material);
      const lampBtn = section.querySelector('[data-lamp-side]');
      if (lampBtn) lampBtn.classList.remove("is-active");
    }
  });

  // clear url param
  const url = new URL(window.location);
  url.searchParams.delete('v');
  window.history.replaceState({}, '', url);
}

function shareState() {
  const url = window.location.origin + window.location.pathname + '?v=' + encodeState();
  navigator.clipboard.writeText(url).then(() => {
    const btn = document.getElementById('shareBtn');
    const original = btn.textContent;
    btn.textContent = 'Copied!';
    setTimeout(() => btn.textContent = original, 2000);
  }).catch(() => {
    prompt('Copy this link:', url);
  });
}

// --- toolbar ---

document.getElementById("rotateBtn").addEventListener("click", () => {
  state.autoRotate = !state.autoRotate;
});

const shareBtn = document.getElementById("shareBtn");
if (shareBtn) shareBtn.addEventListener("click", shareState);

const resetBtn = document.getElementById("resetBtn");
if (resetBtn) resetBtn.addEventListener("click", resetDesign);

// show available mesh names on screen when ?debug=1 is in the URL
if (new URLSearchParams(window.location.search).get('debug') === '1') {
  const panel = document.createElement('div');
  panel.style.cssText = 'position:fixed;top:8px;right:8px;background:rgba(0,0,0,0.8);color:#0f0;font:11px monospace;padding:12px;border-radius:8px;max-height:80vh;overflow-y:auto;z-index:9999;max-width:320px;word-break:break-all;';
  panel.innerHTML = '<strong>Mesh names (loading...)</strong>';
  document.body.appendChild(panel);
  const orig = init;
  // patch: after model loads, fill the panel
  const observer = new MutationObserver(() => {
    if (!state.model) return;
    const names = [];
    state.model.traverse(c => { if (c.isMesh) names.push(c.name); });
    panel.innerHTML = '<strong>Mesh names:</strong><br>' + names.map(n => `• ${n}`).join('<br>');
    observer.disconnect();
  });
  observer.observe(document.getElementById('loading'), { attributes: true, attributeFilter: ['style'] });
}

// --- render loop ---

function animate() {
  requestAnimationFrame(animate);
  controls.update();
  if (state.model && state.autoRotate) state.model.rotation.y += 0.01;
  renderer.render(scene, camera);
}

animate();
