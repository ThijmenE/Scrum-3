// config.js
export const materialPresets = {
  default: { metalness: 0.0, roughness: 0.7, envIntensity: 1.0 },
  hardware: { metalness: 0.95, roughness: 0.12, envIntensity: 1.4 },
  drip: { metalness: 0.92, roughness: 0.15, envIntensity: 1.3 },
  topping: { metalness: 0.0, roughness: 0.6, envIntensity: 1.0 },
  flavors: { metalness: 0.0, roughness: 0.75, envIntensity: 0.6 },
  base: { metalness: 0.0, roughness: 0.8, envIntensity: 0.6 }
};

const CONFIG_BASE = import.meta.url.replace(/\/[^/]+$/, '/');

export async function loadModelConfig(modelName) {
  const key = modelName.toLowerCase().replace(/\.(glb|gltf)$/i, "").replace(/[^a-z0-9]/g, "");
  const path = CONFIG_BASE + 'configs/' + key + '.json';
  const res = await fetch(path);
  if (!res.ok) {
    const fallbackPath = CONFIG_BASE + 'configs/default.json';
    const fallback = await fetch(fallbackPath);
    if (!fallback.ok) throw new Error(`Failed to load config: ${path}`);
    return await fallback.json();
  }
  return await res.json();
}
