-- Water Detection System
-- Makes all water parts fishable areas

local Players = game:GetService("Players")
local ReplicatedStorage = game:GetService("ReplicatedStorage")
local RunService = game:GetService("RunService")

-- Wait for RemoteEvents
local remoteEventsFolder = ReplicatedStorage:WaitForChild("RemoteEvents")
local tryCatchFishEvent = remoteEventsFolder:WaitForChild("TryCatchFishEvent")

-- Water detection settings
local WATER_MATERIALS = {
    Enum.Material.Water,
    Enum.Material.ForceField -- Sometimes used for water effects
}

local WATER_COLORS = {
    Color3.fromRGB(0, 162, 255), -- Blue water
    Color3.fromRGB(0, 100, 200), -- Dark blue
    Color3.fromRGB(0, 150, 255), -- Light blue
    Color3.fromRGB(0, 200, 255), -- Cyan
    Color3.fromRGB(100, 200, 255) -- Light cyan
}

-- Track fishable water parts
local fishableWaterParts = {}
local playerFishingStates = {}

-- Function to check if a part is water
local function isWaterPart(part)
    -- Check material
    if table.find(WATER_MATERIALS, part.Material) then
        return true
    end
    
    -- Check color (for parts that might not have water material)
    local color = part.Color
    for _, waterColor in pairs(WATER_COLORS) do
        local distance = math.abs(color.R - waterColor.R) + math.abs(color.G - waterColor.G) + math.abs(color.B - waterColor.B)
        if distance < 0.3 then -- Close enough color match
            return true
        end
    end
    
    -- Check if part name contains water-related keywords
    local name = part.Name:lower()
    local waterKeywords = {"water", "lake", "river", "pond", "ocean", "sea", "creek", "stream", "pool"}
    for _, keyword in pairs(waterKeywords) do
        if string.find(name, keyword) then
            return true
        end
    end
    
    return false
end

-- Function to add visual effects to water parts
local function addWaterEffects(part)
    -- Add a subtle glow effect
    local pointLight = Instance.new("PointLight")
    pointLight.Color = Color3.fromRGB(0, 150, 255)
    pointLight.Brightness = 0.5
    pointLight.Range = 20
    pointLight.Parent = part
    
    -- Add a subtle particle effect (optional)
    local attachment = Instance.new("Attachment")
    attachment.Parent = part
    
    local particles = Instance.new("ParticleEmitter")
    particles.Parent = attachment
    particles.Texture = "rbxasset://textures/particles/sparkles_main.dds"
    particles.Lifetime = NumberRange.new(1, 3)
    particles.Rate = 10
    particles.SpreadAngle = Vector2.new(45, 45)
    particles.Speed = NumberRange.new(1, 3)
    particles.Color = ColorSequence.new(Color3.fromRGB(0, 150, 255))
    particles.Size = NumberSequence.new{
        NumberSequenceKeypoint.new(0, 0.1),
        NumberSequenceKeypoint.new(0.5, 0.3),
        NumberSequenceKeypoint.new(1, 0)
    }
    particles.Transparency = NumberSequence.new{
        NumberSequenceKeypoint.new(0, 0.5),
        NumberSequenceKeypoint.new(1, 1)
    }
end

-- Function to scan for water parts
local function scanForWaterParts()
    local workspace = game.Workspace
    
    -- Scan all parts in workspace
    for _, part in pairs(workspace:GetDescendants()) do
        if part:IsA("BasePart") and not part:IsA("Terrain") then
            if isWaterPart(part) then
                -- Make it fishable
                fishableWaterParts[part] = true
                
                -- Add click detector for fishing
                local clickDetector = part:FindFirstChild("FishingClickDetector")
                if not clickDetector then
                    clickDetector = Instance.new("ClickDetector")
                    clickDetector.Name = "FishingClickDetector"
                    clickDetector.MaxActivationDistance = 15
                    clickDetector.Parent = part
                    
                    -- Handle fishing clicks
                    clickDetector.MouseClick:Connect(function(clickingPlayer)
                        if clickingPlayer.Character and clickingPlayer.Character:FindFirstChild("HumanoidRootPart") then
                            local distance = (clickingPlayer.Character.HumanoidRootPart.Position - part.Position).Magnitude
                            if distance <= 15 then
                                tryCatchFishEvent:FireServer()
                            end
                        end
                    end)
                end
                
                -- Add visual effects
                addWaterEffects(part)
                
                print("Water part detected and made fishable: " .. part.Name)
            end
        end
    end
end

-- Function to check if player is near any fishable water
local function isPlayerNearWater(player)
    if not player.Character or not player.Character:FindFirstChild("HumanoidRootPart") then
        return false
    end
    
    local playerPosition = player.Character.HumanoidRootPart.Position
    
    for waterPart, _ in pairs(fishableWaterParts) do
        if waterPart.Parent then -- Make sure part still exists
            local distance = (playerPosition - waterPart.Position).Magnitude
            if distance <= 15 then
                return true, waterPart
            end
        end
    end
    
    return false
end

-- Function to show fishing prompt
local function showFishingPrompt(player, waterPart)
    local playerGui = player:WaitForChild("PlayerGui")
    local promptGui = playerGui:FindFirstChild("FishingPrompt")
    
    if promptGui then
        promptGui:Destroy()
    end
    
    promptGui = Instance.new("ScreenGui")
    promptGui.Name = "FishingPrompt"
    promptGui.Parent = playerGui
    
    local frame = Instance.new("Frame")
    frame.Size = UDim2.new(0, 250, 0, 60)
    frame.Position = UDim2.new(0.5, -125, 0.8, 0)
    frame.BackgroundColor3 = Color3.fromRGB(0, 150, 255)
    frame.BorderSizePixel = 0
    frame.Parent = promptGui
    
    local corner = Instance.new("UICorner")
    corner.CornerRadius = UDim.new(0, 10)
    corner.Parent = frame
    
    local label = Instance.new("TextLabel")
    label.Size = UDim2.new(1, 0, 1, 0)
    label.BackgroundTransparency = 1
    label.Text = "🎣 Klik untuk memancing!\n" .. waterPart.Name
    label.TextColor3 = Color3.fromRGB(255, 255, 255)
    label.TextScaled = true
    label.Font = Enum.Font.SourceSansBold
    label.Parent = frame
    
    -- Animate in
    frame.Size = UDim2.new(0, 0, 0, 0)
    local tween = game:GetService("TweenService"):Create(frame, TweenInfo.new(0.3), {Size = UDim2.new(0, 250, 0, 60)})
    tween:Play()
    
    return promptGui
end

-- Function to hide fishing prompt
local function hideFishingPrompt(player)
    local playerGui = player:WaitForChild("PlayerGui")
    local promptGui = playerGui:FindFirstChild("FishingPrompt")
    if promptGui then
        promptGui:Destroy()
    end
end

-- Initialize water detection
spawn(function()
    wait(2) -- Wait for everything to load
    scanForWaterParts()
    
    -- Rescan periodically for new water parts
    while true do
        wait(10) -- Rescan every 10 seconds
        scanForWaterParts()
    end
end)

-- Monitor players for water proximity
spawn(function()
    while true do
        wait(0.5)
        
        for _, player in pairs(Players:GetPlayers()) do
            local nearWater, waterPart = isPlayerNearWater(player)
            
            if nearWater then
                if not playerFishingStates[player.UserId] then
                    playerFishingStates[player.UserId] = {showingPrompt = false}
                end
                
                if not playerFishingStates[player.UserId].showingPrompt then
                    showFishingPrompt(player, waterPart)
                    playerFishingStates[player.UserId].showingPrompt = true
                end
            else
                if playerFishingStates[player.UserId] and playerFishingStates[player.UserId].showingPrompt then
                    hideFishingPrompt(player)
                    playerFishingStates[player.UserId].showingPrompt = false
                end
            end
        end
    end
end)

-- Clean up when players leave
Players.PlayerRemoving:Connect(function(player)
    playerFishingStates[player.UserId] = nil
    hideFishingPrompt(player)
end)

print("Water Detection System initialized! All water areas are now fishable!")