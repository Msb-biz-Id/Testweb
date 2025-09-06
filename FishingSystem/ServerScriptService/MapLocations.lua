-- Map Locations for Fishing System
-- Creates shop and selling locations on the map

local ReplicatedStorage = game:GetService("ReplicatedStorage")
local Workspace = game:GetService("Workspace")

-- Create locations folder
local locationsFolder = Instance.new("Folder")
locationsFolder.Name = "FishingLocations"
locationsFolder.Parent = Workspace

-- Shop Location
local shopLocation = Instance.new("Part")
shopLocation.Name = "EquipmentShop"
shopLocation.Size = Vector3.new(20, 1, 20)
shopLocation.Position = Vector3.new(0, 0.5, 0)
shopLocation.Anchored = true
shopLocation.BrickColor = BrickColor.new("Bright blue")
shopLocation.Material = Enum.Material.Neon
shopLocation.Parent = locationsFolder

-- Shop sign
local shopSign = Instance.new("Part")
shopSign.Name = "ShopSign"
shopSign.Size = Vector3.new(0.2, 8, 12)
shopSign.Position = Vector3.new(0, 5, 0)
shopSign.Anchored = true
shopSign.BrickColor = BrickColor.new("Bright yellow")
shopSign.Material = Enum.Material.Neon
shopSign.Parent = shopLocation

-- Shop text
local shopText = Instance.new("BillboardGui")
shopText.Size = UDim2.new(0, 200, 0, 100)
shopText.StudsOffset = Vector3.new(0, 2, 0)
shopText.Parent = shopSign

local shopTextLabel = Instance.new("TextLabel")
shopTextLabel.Size = UDim2.new(1, 0, 1, 0)
shopTextLabel.BackgroundTransparency = 1
shopTextLabel.Text = "🛒\nEQUIPMENT\nSHOP"
shopTextLabel.TextColor3 = Color3.fromRGB(255, 255, 255)
shopTextLabel.TextScaled = true
shopTextLabel.Font = Enum.Font.SourceSansBold
shopTextLabel.Parent = shopText

-- Shop interaction part
local shopInteraction = Instance.new("Part")
shopInteraction.Name = "ShopInteraction"
shopInteraction.Size = Vector3.new(20, 10, 20)
shopInteraction.Position = Vector3.new(0, 5, 0)
shopInteraction.Anchored = true
shopInteraction.Transparency = 1
shopInteraction.CanCollide = false
shopInteraction.Parent = shopLocation

-- Add ClickDetector to shop
local shopClickDetector = Instance.new("ClickDetector")
shopClickDetector.MaxActivationDistance = 10
shopClickDetector.Parent = shopInteraction

-- Fish Selling Location
local sellLocation = Instance.new("Part")
sellLocation.Name = "FishMarket"
sellLocation.Size = Vector3.new(20, 1, 20)
sellLocation.Position = Vector3.new(50, 0.5, 0)
sellLocation.Anchored = true
sellLocation.BrickColor = BrickColor.new("Bright green")
sellLocation.Material = Enum.Material.Neon
sellLocation.Parent = locationsFolder

-- Sell sign
local sellSign = Instance.new("Part")
sellSign.Name = "SellSign"
sellSign.Size = Vector3.new(0.2, 8, 12)
sellSign.Position = Vector3.new(50, 5, 0)
sellSign.Anchored = true
sellSign.BrickColor = BrickColor.new("Bright yellow")
sellSign.Material = Enum.Material.Neon
sellSign.Parent = sellLocation

-- Sell text
local sellText = Instance.new("BillboardGui")
sellText.Size = UDim2.new(0, 200, 0, 100)
sellText.StudsOffset = Vector3.new(0, 2, 0)
sellText.Parent = sellSign

local sellTextLabel = Instance.new("TextLabel")
sellTextLabel.Size = UDim2.new(1, 0, 1, 0)
sellTextLabel.BackgroundTransparency = 1
sellTextLabel.Text = "💰\nFISH\nMARKET"
sellTextLabel.TextColor3 = Color3.fromRGB(255, 255, 255)
sellTextLabel.TextScaled = true
sellTextLabel.Font = Enum.Font.SourceSansBold
sellTextLabel.Parent = sellText

-- Sell interaction part
local sellInteraction = Instance.new("Part")
sellInteraction.Name = "SellInteraction"
sellInteraction.Size = Vector3.new(20, 10, 20)
sellInteraction.Position = Vector3.new(50, 5, 0)
sellInteraction.Anchored = true
sellInteraction.Transparency = 1
sellInteraction.CanCollide = false
sellInteraction.Parent = sellLocation

-- Add ClickDetector to sell location
local sellClickDetector = Instance.new("ClickDetector")
sellClickDetector.MaxActivationDistance = 10
sellClickDetector.Parent = sellInteraction

-- Fishing Spots
local fishingSpots = {
    {Position = Vector3.new(-30, 0.5, 30), Name = "Lake Shore"},
    {Position = Vector3.new(30, 0.5, 30), Name = "River Bank"},
    {Position = Vector3.new(-30, 0.5, -30), Name = "Pond Edge"},
    {Position = Vector3.new(30, 0.5, -30), Name = "Creek Side"}
}

for i, spot in pairs(fishingSpots) do
    local fishingSpot = Instance.new("Part")
    fishingSpot.Name = "FishingSpot" .. i
    fishingSpot.Size = Vector3.new(15, 1, 15)
    fishingSpot.Position = spot.Position
    fishingSpot.Anchored = true
    fishingSpot.BrickColor = BrickColor.new("Bright blue")
    fishingSpot.Material = Enum.Material.Water
    fishingSpot.Parent = locationsFolder
    
    -- Fishing spot sign
    local spotSign = Instance.new("Part")
    spotSign.Name = "SpotSign"
    spotSign.Size = Vector3.new(0.2, 6, 8)
    spotSign.Position = spot.Position + Vector3.new(0, 3, 0)
    spotSign.Anchored = true
    spotSign.BrickColor = BrickColor.new("Bright yellow")
    spotSign.Material = Enum.Material.Neon
    spotSign.Parent = fishingSpot
    
    -- Spot text
    local spotText = Instance.new("BillboardGui")
    spotText.Size = UDim2.new(0, 150, 0, 60)
    spotText.StudsOffset = Vector3.new(0, 1, 0)
    spotText.Parent = spotSign
    
    local spotTextLabel = Instance.new("TextLabel")
    spotTextLabel.Size = UDim2.new(1, 0, 1, 0)
    spotTextLabel.BackgroundTransparency = 1
    spotTextLabel.Text = "🎣\n" .. spot.Name
    spotTextLabel.TextColor3 = Color3.fromRGB(255, 255, 255)
    spotTextLabel.TextScaled = true
    spotTextLabel.Font = Enum.Font.SourceSansBold
    spotTextLabel.Parent = spotText
end

-- Decorative elements
local decorations = {
    {Position = Vector3.new(0, 2, 15), Size = Vector3.new(2, 4, 2), Color = "Bright green"}, -- Tree near shop
    {Position = Vector3.new(50, 2, 15), Size = Vector3.new(2, 4, 2), Color = "Bright green"}, -- Tree near market
    {Position = Vector3.new(-15, 1, 0), Size = Vector3.new(1, 2, 1), Color = "Bright red"}, -- Lamp post
    {Position = Vector3.new(15, 1, 0), Size = Vector3.new(1, 2, 1), Color = "Bright red"}, -- Lamp post
}

for i, deco in pairs(decorations) do
    local decoration = Instance.new("Part")
    decoration.Name = "Decoration" .. i
    decoration.Size = deco.Size
    decoration.Position = deco.Position
    decoration.Anchored = true
    decoration.BrickColor = BrickColor.new(deco.Color)
    decoration.Material = Enum.Material.Wood
    decoration.Parent = locationsFolder
end

-- Ground
local ground = Instance.new("Part")
ground.Name = "Ground"
ground.Size = Vector3.new(200, 1, 200)
ground.Position = Vector3.new(0, -0.5, 0)
ground.Anchored = true
ground.BrickColor = BrickColor.new("Bright green")
ground.Material = Enum.Material.Grass
ground.Parent = locationsFolder

-- Create large water areas
local waterAreas = {
    -- Large lake in the center
    {Position = Vector3.new(0, 0.5, 0), Size = Vector3.new(80, 1, 60), Name = "Central Lake"},
    -- River flowing through
    {Position = Vector3.new(0, 0.5, 50), Size = Vector3.new(120, 1, 20), Name = "Main River"},
    -- Small ponds
    {Position = Vector3.new(-60, 0.5, -60), Size = Vector3.new(30, 1, 30), Name = "Forest Pond"},
    {Position = Vector3.new(60, 0.5, -60), Size = Vector3.new(25, 1, 25), Name = "Mountain Spring"},
    {Position = Vector3.new(-60, 0.5, 60), Size = Vector3.new(35, 1, 35), Name = "Garden Pool"},
    {Position = Vector3.new(60, 0.5, 60), Size = Vector3.new(40, 1, 40), Name = "Crystal Lake"},
    -- Ocean areas
    {Position = Vector3.new(0, 0.5, 100), Size = Vector3.new(200, 1, 50), Name = "Ocean Shore"},
    {Position = Vector3.new(0, 0.5, -100), Size = Vector3.new(200, 1, 50), Name = "Northern Sea"},
    {Position = Vector3.new(100, 0.5, 0), Size = Vector3.new(50, 1, 200), Name = "Eastern Bay"},
    {Position = Vector3.new(-100, 0.5, 0), Size = Vector3.new(50, 1, 200), Name = "Western Gulf"}
}

for i, waterArea in pairs(waterAreas) do
    local waterPart = Instance.new("Part")
    waterPart.Name = waterArea.Name
    waterPart.Size = waterArea.Size
    waterPart.Position = waterArea.Position
    waterPart.Anchored = true
    waterPart.BrickColor = BrickColor.new("Bright blue")
    waterPart.Material = Enum.Material.Water
    waterPart.CanCollide = false
    waterPart.Parent = locationsFolder
    
    -- Add transparency for better water effect
    waterPart.Transparency = 0.3
    
    -- Add water sign
    local waterSign = Instance.new("Part")
    waterSign.Name = "WaterSign"
    waterSign.Size = Vector3.new(0.2, 4, 6)
    waterSign.Position = waterArea.Position + Vector3.new(0, 2, 0)
    waterSign.Anchored = true
    waterSign.BrickColor = BrickColor.new("Bright yellow")
    waterSign.Material = Enum.Material.Neon
    waterSign.Parent = waterPart
    
    -- Water sign text
    local waterText = Instance.new("BillboardGui")
    waterText.Size = UDim2.new(0, 100, 0, 40)
    waterText.StudsOffset = Vector3.new(0, 1, 0)
    waterText.Parent = waterSign
    
    local waterTextLabel = Instance.new("TextLabel")
    waterTextLabel.Size = UDim2.new(1, 0, 1, 0)
    waterTextLabel.BackgroundTransparency = 1
    waterTextLabel.Text = "🎣\n" .. waterArea.Name
    waterTextLabel.TextColor3 = Color3.fromRGB(255, 255, 255)
    waterTextLabel.TextScaled = true
    waterTextLabel.Font = Enum.Font.SourceSansBold
    waterTextLabel.Parent = waterText
end

-- Create additional water features
local additionalWater = {
    -- Waterfalls
    {Position = Vector3.new(0, 5, 80), Size = Vector3.new(10, 10, 2), Name = "Waterfall"},
    {Position = Vector3.new(40, 3, -40), Size = Vector3.new(8, 6, 2), Name = "Small Falls"},
    -- Water streams
    {Position = Vector3.new(-30, 0.5, 0), Size = Vector3.new(5, 1, 100), Name = "Stream 1"},
    {Position = Vector3.new(30, 0.5, 0), Size = Vector3.new(5, 1, 100), Name = "Stream 2"},
    {Position = Vector3.new(0, 0.5, -30), Size = Vector3.new(100, 1, 5), Name = "Stream 3"},
    {Position = Vector3.new(0, 0.5, 30), Size = Vector3.new(100, 1, 5), Name = "Stream 4"}
}

for i, waterFeature in pairs(additionalWater) do
    local waterPart = Instance.new("Part")
    waterPart.Name = waterFeature.Name
    waterPart.Size = waterFeature.Size
    waterPart.Position = waterFeature.Position
    waterPart.Anchored = true
    waterPart.BrickColor = BrickColor.new("Bright blue")
    waterPart.Material = Enum.Material.Water
    waterPart.CanCollide = false
    waterPart.Transparency = 0.2
    waterPart.Parent = locationsFolder
end

print("Fishing System map locations created successfully!")
print("All water areas are now fishable!")