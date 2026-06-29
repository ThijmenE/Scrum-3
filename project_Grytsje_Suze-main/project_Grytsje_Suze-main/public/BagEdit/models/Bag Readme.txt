# BagEdit — 3D Bag Customizer

BagEdit is een zelfstandige 3D customizer module die in de portfolio geïntegreerd is. Klanten kunnen tassen live aanpassen met kleuren en accessoires.

## Structuur

```
BagEdit/
├── app.js              ← Hoofdlogica (Three.js, laadt modellen, verwerkt input)
├── styles.css          ← Stijlen voor de customizer UI
├── config.js           ← Laadt de juiste JSON config op basis van modelnaam
├── configs/            ← Per-tas configuraties (JSON)
│   ├── default.json    ← Fallback als geen config gevonden wordt
│   ├── bagnum.json
│   ├── glasinlood.json
│   ├── hartjestas.json
│   └
├── models/             ← 3D modellen (.glb bestanden)
│   ├── bagnum.glb
│   ├── glasinlood.glb
│   ├── hartjestas.glb
│   └── IjsBase.glb
└── Assets/
    └── icons/          ← Pictogrammen voor toggle knoppen
```

## Hoe werkt het?

1. De portfolio pagina (`portfolio-show.php`) geeft het 3D model door via `HUIDIG_3D_MODEL`
2. `config.js` zoekt de bijbehorende JSON in `configs/`
3. De customizer toont groepen (bijv. "Flavors", "Hardware", "Pattern")
4. Elke groep heeft kleurschakelaars of mesh-toggle knoppen
5. Gebruikers kunnen hun ontwerp delen via een URL

## Configuratie opbouwen

Elke JSON in `configs/` heeft de volgende opbouw:

```json
{
  "groepsnaam": {
    "title": "Weergavenaam",
    "subtitle": "Optionele subtitel",
    "meshNames": ["NaamVanMeshInGLB"],
    "swatches": [
      { "name": "Label", "color": "#HEXCODE" }
    ],
    "defaultColor": "#HEXCODE",
    "toggle": false
  }
}
```

### Velden uitleg

| Veld | Type | Beschrijving |
|------|------|--------------|
| `title` | string | Titel die getoond wordt in de sidebar |
| `subtitle` | string | Optionele uitlegtekst |
| `meshNames` | array | Namen van meshes in de GLB die deze groepbesturen |
| `swatches` | array | Kleuropties die de gebruiker kan kiezen |
| `defaultColor` | string | Hex kleur die standaard geselecteerd is |
| `toggle` | boolean | `true` = toont mesh-toggle knoppen (aan/uit), `false` = alleen kleurknoppen |
| `meshOptions` | array | Opties voor toggle groepen (bijv. `["None", "Swirl", "Drip"]`) |
| `defaultMesh` | string | Standaard geselecteerde mesh bij toggle groepen |
| `singleChoice` | boolean | Alleen één tegelijkertijd zichtbaar bij toggle |

### Mesh Namen

De `meshNames` moeten precies overeenkomen met de namen van de meshes in je Blender output. Controleer de namen in de GLB of in Blender's outliner. De matching is case-insensitive maar moet exact zijn op de naam (zonder extensies of extra tekens).

### Voorbeeld: Enkele kleurgroep

```json
{
  "flavors": {
    "title": "Kleuren",
    "meshNames": ["Body"],
    "swatches": [
      { "name": "Wit", "color": "#FFFFFF" },
      { "name": "Zwart", "color": "#1A1A1A" }
    ],
    "defaultColor": "#FFFFFF",
    "toggle": false
  }
}
```

### Voorbeeld: Toggle groep (aan/uit)

```json
{
  "drip": {
    "title": "Drip of Swirl",
    "meshNames": ["Drip", "Swirl"],
    "meshOptions": ["None", "Swirl", "Drip"],
    "defaultMesh": "None",
    "swatches": [
      { "name": "Zilver", "color": "#E8E8E8" },
      { "name": "Goud", "color": "#FFD700" }
    ],
    "toggle": true,
    "singleChoice": true
  }
}
```

### Voorbeeld: Multi-kleur patroon

Elke swatch kan meerdere kleuren bevatten, één per mesh. Dit is handig voor patronen waarbij verschillende onderdelen verschillende kleuren krijgen:

```json
{
  "pattern": {
    "title": "Patroon",
    "meshNames": ["Blue", "Green", "Yellow", "Pink", "Orange"],
    "swatches": [
      {
        "name": "Klassiek",
        "colors": {
          "Blue": "#4488ff",
          "Green": "#44cc44",
          "Yellow": "#ffdd44",
          "Pink": "#ff44aa",
          "Orange": "#ff8844"
        }
      },
      {
        "name": "Zonsondergang",
        "colors": {
          "Blue": "#ff4400",
          "Green": "#ff8800",
          "Yellow": "#ffcc00",
          "Pink": "#ff0066",
          "Orange": "#ff6600"
        }
      }
    ],
    "defaultSwatch": "Klassiek",
    "toggle": false
  }
}
```

## Nieuwe tas toevoegen

### 1. GLB model uploaden

Exporteer je tas uit Blender als `.glb`:
- Zorg dat alle textures in de GLB gepacked zijn (File → External Data → Pack All Into .blend)
- Upload het bestand via het admin panel
- Noteer de bestandsnaam (bijv. `mijn-tas.glb`)

### 2. Config JSON aanmaken

Maak een nieuw bestand in `public/BagEdit/configs/`, bijvoorbeeld `mijn-tas.json`:

```json
{
  "flavors": {
    "title": "Kleuren",
    "meshNames": ["Body"],
    "swatches": [
      { "name": "Wit", "color": "#FFFFFF" },
      { "name": "Zwart", "color": "#1A1A1A" }
    ],
    "defaultColor": "#FFFFFF",
    "toggle": false
  }
}
```

### 3. Database aanpassen

Voeg de tas toe in de database via het admin panel (`/admin/tassen`):
- Vul naam, beschrijving en afbeelding in
- Bij **3D Model** selecteer je het geüploade `.glb` bestand

De website laadt automatisch de juiste config op basis van de bestandsnaam.

## Materiaal presets

In `config.js` staan globale PBR (physically based rendering) instellingen per materiaalgroep:

```js
hardware: { metalness: 0.95, roughness: 0.12 }   ← metaal
drip:     { metalness: 0.92, roughness: 0.15 }   ← glanzend
flavors:  { metalness: 0.0,  roughness: 0.75 }   ← leer/stof
```

Pas deze aan als je materiaaltypes aanpast.

## Troubleshooting

### "Cannot read image.png"
De GLB zoekt een externe texture die niet in het bestand zit. In Blender: **File → External Data → Pack All Into .blend**, daarna opnieuw exporteren.

### Mesh naam niet gevonden
Controleer of de `meshNames` in de JSON precies overeenkomen met de mesh namen in Blender. De matching is case-insensitive maar moet exact zijn op de naam.

### Kleuren veranderen niet
Controleer of de hex codes geldig zijn (beginnen met `#`) en of de mesh naam correct gespeld is in de config.

## Beheer

- Admin login: `/admin/login`
- Tassen beheren: `/admin/tassen`
- Gebruikers beheren: `/admin/gebruikers`
