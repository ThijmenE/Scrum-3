import * as THREE from 'three';

import { OrbitControls } from 'three/addons/controls/OrbitControls.js';

import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
// SCENE

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

renderer.domElement.style.width = "100%";
renderer.domElement.style.height = "100%";
renderer.domElement.style.display = "block";

viewerElement.appendChild(renderer.domElement);

camera.aspect = viewerElement.clientWidth / viewerElement.clientHeight;
camera.updateProjectionMatrix();

// LIGHTING — 3-point lighting setup for product visualization

const ambientLight = new THREE.AmbientLight(0xffffff, 0.8);
scene.add(ambientLight);

// Key light — main directional light from front-right
const keyLight = new THREE.DirectionalLight(0xffffff, 1.2);
keyLight.position.set(8, 6, 8);
scene.add(keyLight);

// Fill light — opposite side to soften shadows
const fillLight = new THREE.DirectionalLight(0xffffff, 0.6);
fillLight.position.set(-6, 4, -6);
scene.add(fillLight);

// Back light — adds rim lighting and separation
const backLight = new THREE.DirectionalLight(0xffffff, 0.8);
backLight.position.set(0, 8, -10);
scene.add(backLight);

// FLOOR

const floorGeometry = new THREE.PlaneGeometry(20, 20);

const floorMaterial = new THREE.MeshStandardMaterial({
  color: 0x333333
});

const floor = new THREE.Mesh(
  floorGeometry,
  floorMaterial
);

floor.rotation.x = -Math.PI / 2;

floor.position.y = -1.5;

scene.add(floor);

floor.visible = false;

// CONTROLS

const controls = new OrbitControls(
  camera,
  renderer.domElement
);

controls.enableDamping = true;

controls.dampingFactor = 0.05;

// MODEL

const loader = new GLTFLoader();

let model;
let materials = {};
let toppingLayers = {
  sprinkles: true,
  pearls: false,
  crystals: false
};
let toppingMeshes = {
  sprinkles: [],
  pearls: [],
  crystals: []
};

let dripLayers = {
  goldDrip: false
};
let dripMeshes = {
  goldDrip: []
};

const toppingMeshNames = {
  sprinkles: ["Sprinkles", "Sprinkles1", "Sprinkles2", "Sprinkles3"],
  pearls: [],
  crystals: []
};

const allToppingMeshNames = [
  ...toppingMeshNames.sprinkles,
  ...toppingMeshNames.pearls,
  ...toppingMeshNames.crystals
];

// Explicit mesh names for each drip layer
const dripMeshNames = {
  goldDrip: ["Drip"]
};

function getMeshNameFromMaterialName(materialName) {
  return String(materialName || "").replace(/\s-\sMaterial(?:\s\d+)?$/, "").trim();
}

const materialGroups = {
  flavors: {
    title: "Flavours",
    subtitle: "(Base color)",
    meshNames: ["Body"],
    swatches: [
      { name: "Vanilla", color: "#f5ead9" },
      { name: "Mint", color: "#d4f1d4" },
      { name: "Pistache", color: "#e8f542" },
      { name: "Lemon", color: "#ffeb3b" },
      { name: "Mango", color: "#ffb74d" },
      { name: "Bubblegum", color: "#f47fff" },
      { name: "Raspberry", color: "#d81b60" },
      { name: "Blackberry", color: "#9c27b0" },
      { name: "Cherry", color: "#c62828" },
      { name: "Chocolate", color: "#8d6e63" }
    ]
  },
  drip: {
    title: "Drip of Swirl",
    subtitle: "",
    meshNames: ["Drip"],
    swatches: [
      { name: "Geen", color: "#ffffff" },
      { name: "Silver", color: "#e8e8e8" },
      { name: "Gold Caramel", color: "#daa520", accent: true },
      { name: "Rose gold", color: "#f4a0a8" },
      { name: "Dark chocolate", color: "#6d4c41" }
    ]
  },
  topping: {
    title: "Topping",
    subtitle: "",
    meshNames: ["Cylinder", "Cylinder_1"],
    swatches: [
      { name: "Geen", color: "#ffffff" },
      { name: "Discodip/Sprinkels", color: "#ff8c00", accent: true },
      { name: "Pearls", color: "#fffacd", accent: true },
      { name: "Crystals", color: "#87ceeb", accent: true }
    ]
  },
  hardware: {
    title: "Hardware",
    subtitle: "(Zipper, Chain, Logo)",
    meshNames: ["Ring", "Chain", "Zipper", "Logo"],
    swatches: [
      { name: "Silver", color: "#e0e0e0" },
      { name: "Gold", color: "#ffd700" }
    ]
  }
};

function buildSwatchBackground(swatch) {
  if (swatch.accent) {
    return `linear-gradient(135deg, ${swatch.color} 0%, ${swatch.color} 40%, #ffffff 40%, #ffffff 60%, ${swatch.color} 60%, ${swatch.color} 100%)`;
  }

  return swatch.color;
}

function updateToppingLayerVisibility() {
  if (!model) return;

  toppingMeshes.sprinkles.forEach(mesh => {
    mesh.visible = toppingLayers.sprinkles;
  });

  toppingMeshes.pearls.forEach(mesh => {
    mesh.visible = toppingLayers.pearls;
  });

  toppingMeshes.crystals.forEach(mesh => {
    mesh.visible = toppingLayers.crystals;
  });
}

function updateDripLayerVisibility() {
  if (!model) return;

  dripMeshes.goldDrip.forEach(mesh => {
    mesh.visible = dripLayers.goldDrip;
  });
}

function setMaterialColor(material, color, groupName) {
  if (!material) return;

  material.color.set(color);

  // Only apply metallic finish to hardware and drip
  if (groupName === "hardware" || groupName === "drip") {
    if ("metalness" in material) material.metalness = 0.9;
    if ("roughness" in material) material.roughness = 0.2;
  }

  if (material.map) {
    material.map = null;
  }

  material.needsUpdate = true;
}

function findMatchedMaterials(group) {
  const matched = [];
  const entries = Object.entries(materials);

  if (!entries.length || !Array.isArray(group.meshNames) || !group.meshNames.length) {
    return matched;
  }

  entries.forEach(([name, material]) => {
    const meshName = getMeshNameFromMaterialName(name);
    if (group.meshNames.includes(meshName)) {
      matched.push({ name, material });
    }
  });

  return matched;
}

function applySwatch(groupName, swatch, button) {
  const group = materialGroups[groupName];
  const targets = group.targets || [];

  // Handle drip layer visibility
  if (groupName === "drip") {
    dripLayers.goldDrip = swatch.name !== "Geen";
    updateDripLayerVisibility();
  }

  // Handle topping layer visibility
  if (groupName === "topping") {
    if (swatch.name === "Geen") {
      toppingLayers.sprinkles = false;
      toppingLayers.pearls = false;
      toppingLayers.crystals = false;
    } else if (swatch.name === "Discodip/Sprinkels") {
      toppingLayers.sprinkles = true;
      toppingLayers.pearls = false;
      toppingLayers.crystals = false;
    } else if (swatch.name === "Pearls") {
      toppingLayers.pearls = true;
      toppingLayers.sprinkles = false;
      toppingLayers.crystals = false;
    } else if (swatch.name === "Crystals") {
      toppingLayers.crystals = true;
      toppingLayers.sprinkles = false;
      toppingLayers.pearls = false;
    }
    updateToppingLayerVisibility();
  }

  // Apply color
  if (groupName === "drip") {
    targets.forEach(({ material }) => {
      setMaterialColor(material, swatch.color, groupName);
    });
  } else if (groupName === "topping") {
    targets.forEach(({ name, material }) => {
      const meshName = getMeshNameFromMaterialName(name);
      if (!allToppingMeshNames.includes(meshName)) {
        setMaterialColor(material, swatch.color, groupName);
      }
    });
  } else {
    targets.forEach(({ material }) => {
      setMaterialColor(material, swatch.color, groupName);
    });
  }

  document.querySelectorAll(".swatch-btn").forEach((element) => {
    element.classList.remove("is-active");
  });

  if (button) {
    button.classList.add("is-active");
  }
}

function buildGroupUI() {
  const groupsContainer = document.getElementById("groups");
  groupsContainer.innerHTML = "";

  Object.entries(materialGroups).forEach(([groupName, group]) => {
    const section = document.createElement("section");
    section.className = "group";

    const title = document.createElement("h2");
    title.className = "group-title";
    title.textContent = group.title;
    section.appendChild(title);

    if (group.subtitle) {
      const subtitle = document.createElement("p");
      subtitle.className = "group-subtitle";
      subtitle.textContent = group.subtitle;
      section.appendChild(subtitle);
    }

    const swatchList = document.createElement("div");
    swatchList.className = "swatch-list";

    group.swatches.forEach((swatch) => {
      const button = document.createElement("button");
      button.className = "swatch-btn";
      button.type = "button";

      const chip = document.createElement("span");
      chip.className = "swatch-chip";
      chip.style.background = buildSwatchBackground(swatch);

      const label = document.createElement("span");
      label.className = "swatch-label";
      label.textContent = swatch.name;

      button.appendChild(chip);
      button.appendChild(label);

      button.addEventListener("click", () => applySwatch(groupName, swatch, button));

      swatchList.appendChild(button);
    });

    section.appendChild(swatchList);
    groupsContainer.appendChild(section);
  });
}

loader.load(

  './IjsBase.glb',

  function(gltf) {

    model = gltf.scene;

    // SCALE

    model.scale.set(1, 1, 1);

    // POSITION

    model.position.set(0, -1, 0);

    // EXTRACT MATERIALS


    model.traverse((child) => {
      if (child.isMesh) {
        const childName = String(child.name || "").trim();

        if (Array.isArray(child.material)) {
          child.material.forEach((mat, idx) => {
            const matName = childName + " - Material " + idx;
            materials[matName] = mat;
          });
        } else if (child.material) {
          const matName = childName + " - Material";
          materials[matName] = child.material;
        }

        // Categorize topping meshes by explicit names
        if (toppingMeshNames.sprinkles.includes(childName)) {
          toppingMeshes.sprinkles.push(child);
        } else if (toppingMeshNames.pearls.includes(childName)) {
          toppingMeshes.pearls.push(child);
        } else if (toppingMeshNames.crystals.includes(childName)) {
          toppingMeshes.crystals.push(child);
        }

        // Categorize drip meshes by explicit names
        if (dripMeshNames.goldDrip.includes(childName)) {
          dripMeshes.goldDrip.push(child);
        }
      }
    });

    Object.entries(materialGroups).forEach(([groupName, group]) => {
      group.targets = findMatchedMaterials(group);
      group.defaultMaterialName = group.targets[0]?.name || null;
    });

    buildGroupUI();

    const firstAvailableGroupName = Object.keys(materialGroups).find((groupName) => materialGroups[groupName].targets && materialGroups[groupName].targets.length);
    if (firstAvailableGroupName) {
      const firstSwatch = materialGroups[firstAvailableGroupName].swatches[0];
      const firstButton = document.querySelector(`.group .swatch-btn`);
      applySwatch(firstAvailableGroupName, firstSwatch, firstButton);
    }

    // Initialize layers
    updateToppingLayerVisibility();
    updateDripLayerVisibility();

    scene.add(model);

    document.getElementById("loading").style.display = "none";
  },

  undefined,

  function(error) {

    console.error(error);

    alert("Failed to load GLB model.");

  }

);

let autoRotate = false;

const rotateBtn =
document.getElementById("rotateBtn");

rotateBtn.addEventListener("click", () => {

  autoRotate = !autoRotate;

});
window.addEventListener("resize", () => {

  const viewerWidth = viewerElement.clientWidth;
  const viewerHeight = viewerElement.clientHeight;

  camera.aspect = viewerWidth / viewerHeight;

  camera.updateProjectionMatrix();

  renderer.setSize(
    viewerWidth,
    viewerHeight
  );

});

//////////////////////////////////////////////////////
// ANIMATION LOOP
//////////////////////////////////////////////////////

function animate() {

  requestAnimationFrame(animate);

  controls.update();

  if (model && autoRotate) {

    model.rotation.y += 0.01;

  }

  renderer.render(scene, camera);

}

animate();
