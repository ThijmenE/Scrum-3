import * as THREE from 'three';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
import { loadModelConfig, materialPresets } from './config.js';

// =====================================================
// MODEL RESOLUTION + DEBUG
// =====================================================

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

// =====================================================
// SCENE SETUP
// =====================================================

const scene = new THREE.Scene();
scene.background = null;

// CAMERA
const camera = new THREE.PerspectiveCamera(
  75,
  window.innerWidth / window.innerHeight,
  0.1,
  1000
);
camera.position.set(0, 1.5, 4);

// RENDERER
const renderer = new THREE.WebGLRenderer({
  antialias: true,
  alpha: true
});

const viewerElement = document.getElementById("viewer");
renderer.setSize(viewerElement.clientWidth, viewerElement.clientHeight);
renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
renderer.setClearColor(0x000000, 0);
renderer.shadowMap.enabled = true;
renderer.outputColorSpace = THREE.SRGBColorSpace;
renderer.toneMapping = THREE.ACESFilmicToneMapping;
renderer.toneMappingExposure = 0.8;
viewerElement.appendChild(renderer.domElement);

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

// CONTROLS
const controls = new OrbitControls(camera, renderer.domElement);
controls.enableDamping = true;

// LIGHTS
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

// =====================================================
// STATE
// =====================================================

const state = {
  model: null,
  materials: {},
  meshes: {},
  layers: {},
  activeMesh: {}, // Track active mesh for each group (e.g., { drip: "Drip" })
  selectedSwatches: {},
  autoRotate: false
};

// =====================================================
// HELPERS
// =====================================================

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

function setMaterialColor(material, color, groupName) {
  if (!material) return;
  
  if (groupName === "pattern") {
    material = material.clone();
  }
  
  material.color.set(color);
  applyMaterialPreset(material, groupName);
  if (material.map) material.map = null;
  material.needsUpdate = true;
}

// =====================================================
// MESH TOGGLING LOGIC
// =====================================================

// Toggle visibility for a specific mesh in a group
function toggleMesh(groupName, meshName, isVisible) {
  if (!state.meshes[groupName] || !state.meshes[groupName][meshName]) return;
  state.meshes[groupName][meshName].forEach(mesh => {
    mesh.visible = isVisible;
  });
  if (!state.layers[groupName]) state.layers[groupName] = {};
  state.layers[groupName][meshName] = isVisible;
}

// Set the active mesh for a group and update visibility
function setActiveMesh(groupName, meshName, materialGroups) {
  const group = materialGroups[groupName];
  if (!group || !group.toggle) return;

  state.activeMesh[groupName] = meshName;

  // Hide everything
  Object.keys(state.meshes[groupName] || {}).forEach(m => {
    toggleMesh(groupName, m, false);
  });

  // None = show nothing
  if (meshName === "None") {
    document.querySelectorAll(
      `.mesh-toggle-btn[data-group="${groupName}"]`
    ).forEach(btn => {
      btn.classList.remove("is-active");

      if (btn.dataset.mesh === "None") {
        btn.classList.add("is-active");
      }
    });

    return;
  }

  // Show selected mesh
  if (state.meshes[groupName][meshName]) {
    toggleMesh(groupName, meshName, true);
  }

  document.querySelectorAll(
    `.mesh-toggle-btn[data-group="${groupName}"]`
  ).forEach(btn => {
    btn.classList.remove("is-active");

    if (btn.dataset.mesh === meshName) {
      btn.classList.add("is-active");
    }
  });
}

// Apply color to the active mesh in a group
function applyColorToActiveMesh(groupName, color) {
  const activeMeshName = state.activeMesh[groupName];
  if (!activeMeshName) return;

  // Apply color to all materials in the active mesh
  if (state.meshes[groupName] && state.meshes[groupName][activeMeshName]) {
    state.meshes[groupName][activeMeshName].forEach(mesh => {
      if (mesh.material) {
        if (Array.isArray(mesh.material)) {
          mesh.material.forEach(mat => setMaterialColor(mat, color, groupName));
        } else {
          setMaterialColor(mesh.material, color, groupName);
        }
      }
    });
  }
}

// =====================================================
// SWATCH APPLY
// =====================================================

function applySwatch(groupName, swatch, btn, materialGroups) {
  const group = materialGroups[groupName];
  if (!group) {
    console.warn("Group not found:", groupName);
    return;
  }

  // Remove .is-active from all swatches in this group
  document.querySelectorAll(`.swatch-btn[data-group="${groupName}"]`).forEach(b => {
    b.classList.remove("is-active");
  });

  // Add .is-active to the clicked button
  if (btn) {
    btn.classList.add("is-active");
    btn.setAttribute("data-group", groupName);
  }

  // Multi-color swatch (per-mesh colors)
  if (swatch.colors && typeof swatch.colors === 'object') {
    Object.entries(swatch.colors).forEach(([meshName, color]) => {
      if (state.meshes[groupName] && state.meshes[groupName][meshName]) {
        state.meshes[groupName][meshName].forEach(mesh => {
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
      }
    });
  } else if (swatch.color) {
    // Single color swatch (legacy behavior)
    if (group.toggle) {
      applyColorToActiveMesh(groupName, swatch.color);
    } else {
      if (group.targets) {
        group.targets.forEach(({ material }) => {
          setMaterialColor(material, swatch.color, groupName);
        });
      }
    }
  }

  state.selectedSwatches[groupName] = swatch;
}

// =====================================================
// UI
// =====================================================

function buildGroupUI(materialGroups) {
  const container = document.getElementById("groups");
  container.innerHTML = "";

  Object.entries(materialGroups).forEach(([groupName, group]) => {
    const section = document.createElement("section");
    const title = document.createElement("h2");
    title.textContent = group.title;
    section.appendChild(title);

    if (group.subtitle) {
      const subtitle = document.createElement("p");
      subtitle.textContent = group.subtitle;
      subtitle.className = "group-subtitle";
      section.appendChild(subtitle);
    }

    // Add mesh toggle buttons if this group is toggleable
    if (group.toggle && group.meshOptions) {
      const toggleWrap = document.createElement("div");
      toggleWrap.className = "toggle-group";

      group.meshOptions.forEach(meshName => {
        const btn = document.createElement("button");
        btn.className = "mesh-toggle-btn";
        btn.setAttribute("data-group", groupName);
        btn.setAttribute("data-mesh", meshName);

        // Use a default color for the chip (e.g., gray)
        btn.innerHTML = `
          <span class="swatch-chip"></span>
          <span class="swatch-label">${meshName}</span>
        `;

        btn.onclick = () => {
          setActiveMesh(groupName, meshName, materialGroups);
        };

        // Mark the default active mesh as active
        if (state.activeMesh[groupName] === meshName) {
          btn.classList.add("is-active");
        }

        toggleWrap.appendChild(btn);
      });

      section.appendChild(toggleWrap);
    }

    // Add color swatches
    if (group.swatches && group.swatches.length > 0) {
      const swatchWrap = document.createElement("div");
      swatchWrap.className = "swatch-list";

      group.swatches.forEach((swatch) => {
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

// =====================================================
// RESIZE HANDLING
// =====================================================

function onWindowResize() {
  const rect = viewerElement.getBoundingClientRect();
  const w = rect.width;
  const h = rect.height;

  camera.aspect = w / h;
  camera.updateProjectionMatrix();
  renderer.setSize(w, h);
}

// Initialize resize handler
onWindowResize();
window.addEventListener("resize", onWindowResize);

// =====================================================
// =====================================================
// LOADER
// =====================================================

const loader = new GLTFLoader();

// =====================================================
// INIT
// =====================================================

async function init() {
  const loading = document.getElementById("loading");
  
  try {
    const materialGroups = await getActiveMaterialGroups();
    console.log("Matched config for model:", getModelName(), "| Groups:", Object.keys(materialGroups));
    
    const model = await new Promise((resolve, reject) => {
      loader.load(HUIDIG_3D_MODEL, resolve, undefined, reject);
    });
    
    state.model = model.scene;
    state.model.position.set(0, -1, 0);

    // Initialize state.meshes, state.layers, and state.activeMesh
    Object.keys(materialGroups).forEach(groupName => {
      state.meshes[groupName] = {};
      state.layers[groupName] = {};
    });

    // Assign materials and meshes
    state.model.traverse((child) => {
      if (!child.isMesh) return;

      // Assign materials
      if (Array.isArray(child.material)) {
        child.material.forEach((mat, i) => {
          state.materials[child.name + i] = mat;
        });
      } else {
        state.materials[child.name] = child.material;
      }

      // Assign meshes to state.meshes based on group configs
      Object.entries(materialGroups).forEach(([groupName, group]) => {
        const cleanName = child.name.replace(/\s-\sMaterial(?:\s\d+)?$/i, "").trim();
        group.meshNames.forEach(meshName => {
          if (cleanName.toLowerCase() === meshName.toLowerCase()) {
            if (!state.meshes[groupName][meshName]) {
              state.meshes[groupName][meshName] = [];
            }
            state.meshes[groupName][meshName].push(child);
          }
        });
      });
    });

      // Assign targets for material groups
      Object.entries(materialGroups).forEach(([groupName, group]) => {
        group.targets = Object.entries(state.materials)
          .filter(([name]) => {
            const cleanName = name.replace(/\s-\sMaterial(?:\s\d+)?$/i, "").trim();
            return group.meshNames.some(m =>
              cleanName.toLowerCase() === m.toLowerCase() ||
              cleanName.toLowerCase().includes(m.toLowerCase())
            );
          })
          .map(([name, material]) => ({ name, material }));

        group.targets.forEach(({ material }) => {
          applyMaterialPreset(material, groupName);
        });
      });

      // Log mesh assignment for debugging
      Object.entries(materialGroups).forEach(([groupName, group]) => {
        console.log(`[Meshes] Group "${groupName}":`, Object.keys(state.meshes[groupName] || {}));
      });

    // Set default mesh visibility
    Object.entries(materialGroups).forEach(([groupName, group]) => {
      if (!group.toggle) return;

      // Hide everything first
      Object.keys(state.meshes[groupName] || {}).forEach(meshName => {
        toggleMesh(groupName, meshName, false);
      });

      const defaultMesh =
        group.defaultMesh ||
        group.meshOptions?.[0];

      if (
        defaultMesh &&
        defaultMesh !== "None" &&
        state.meshes[groupName]?.[defaultMesh]
      ) {
        state.activeMesh[groupName] = defaultMesh;
        toggleMesh(groupName, defaultMesh, true);
      } else {
        state.activeMesh[groupName] = "None";
      }
    });

    buildGroupUI(materialGroups);
    state._lastMaterialGroups = materialGroups;
    scene.add(state.model);
    onWindowResize();

    // Apply default colors/swatches first
    Object.entries(materialGroups).forEach(([groupName, group]) => {
      if (!group.swatches || group.swatches.length === 0) return;

      if (group.defaultSwatch) {
        const defaultSwatch = group.swatches.find(s => s.name === group.defaultSwatch);
        if (!defaultSwatch || !defaultSwatch.colors) return;
        const allBtns = Array.from(document.querySelectorAll(`.swatch-btn[data-group="${groupName}"]`));
        const defaultIdx = group.swatches.findIndex(s => s.name === group.defaultSwatch);
        const matchingBtn = allBtns[defaultIdx];
        if (matchingBtn) {
          applySwatch(groupName, defaultSwatch, matchingBtn, materialGroups);
        }
        return;
      }

      if (!group.defaultColor) return;
      const defaultIdx = group.swatches.findIndex(s => s.color === group.defaultColor);
      if (defaultIdx < 0) return;
      const allBtns = Array.from(document.querySelectorAll(`.swatch-btn[data-group="${groupName}"]`));
      const matchingBtn = allBtns[defaultIdx];
      if (matchingBtn) {
        applySwatch(groupName, group.swatches[defaultIdx], matchingBtn, materialGroups);
      }
    });

    // Restore from URL state if present (overrides defaults)
    const urlParams = new URLSearchParams(window.location.search);
    const savedState = urlParams.get('v');
    if (savedState) {
      const decoded = decodeState(savedState);
      if (decoded) {
        applyState(decoded);
        console.log('Restored design from URL');
      }
    }
  } catch (err) {
    console.error("Load error:", err);
  } finally {
    if (loading) loading.style.display = "none";
  }
}

init();

// =====================================================
// STATE PERSISTENCE (URL sharing)
// =====================================================

function encodeState() {
  const modelName = getModelName();
  const stateData = { model: modelName, groups: {} };

  Object.entries(state.selectedSwatches).forEach(([groupName, swatch]) => {
    if (!swatch) return;
    const materialGroups = state._lastMaterialGroups || {};
    const group = materialGroups[groupName];
    if (!group) return;

    if (swatch.colors) {
      // Pattern/multi-color swatch
      const idx = group.swatches.findIndex(s => s.name === swatch.name);
      if (idx >= 0) stateData.groups[groupName] = { swatchIdx: idx };
    } else if (swatch.color) {
      // Single color swatch
      const idx = group.swatches.findIndex(s => s.color === swatch.color);
      if (idx >= 0) stateData.groups[groupName] = { swatchIdx: idx };
    }
  });

  // Save active mesh for toggle groups
  Object.entries(state.activeMesh).forEach(([groupName, meshName]) => {
    if (!stateData.groups[groupName]) stateData.groups[groupName] = {};
    stateData.groups[groupName].mesh = meshName;
  });

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
  if (!stateData || !stateData.groups) return;
  const materialGroups = state._lastMaterialGroups;
  if (!materialGroups) return;

  Object.entries(stateData.groups).forEach(([groupName, groupState]) => {
    const group = materialGroups[groupName];
    if (!group) return;

    // Apply active mesh first (for toggle groups)
    if (groupState.mesh && group.toggle) {
      setActiveMesh(groupName, groupState.mesh, materialGroups);
    }

    // Apply swatch color
    if (groupState.swatchIdx !== undefined && group.swatches[groupState.swatchIdx]) {
      const swatch = group.swatches[groupState.swatchIdx];
      const allBtns = Array.from(document.querySelectorAll(`.swatch-btn[data-group="${groupName}"]`));
      const matchingBtn = allBtns[groupState.swatchIdx];
      if (matchingBtn) {
        applySwatch(groupName, swatch, matchingBtn, materialGroups);
      }
    }
  });
}

function shareState() {
  const encoded = encodeState();
  const url = window.location.origin + window.location.pathname + '?v=' + encoded;
  navigator.clipboard.writeText(url).then(() => {
    const btn = document.getElementById('shareBtn');
    const originalText = btn.textContent;
    btn.textContent = 'Copied!';
    setTimeout(() => btn.textContent = originalText, 2000);
  }).catch(() => {
    prompt('Copy this link:', window.location.origin + window.location.pathname + '?v=' + encoded);
  });
}

// =====================================================
// TOOLBAR
// =====================================================

document.getElementById("rotateBtn").addEventListener("click", () => {
  state.autoRotate = !state.autoRotate;
});

const shareBtn = document.getElementById("shareBtn");
if (shareBtn) {
  shareBtn.addEventListener("click", shareState);
}

// =====================================================
// LOOP
// =====================================================

function animate() {
  requestAnimationFrame(animate);
  controls.update();
  if (state.model && state.autoRotate) {
    state.model.rotation.y += 0.01;
  }
  renderer.render(scene, camera);
}

animate();