-- Location Interaction System
-- Handles interactions with shop and selling locations

local Players = game:GetService("Players")
local ReplicatedStorage = game:GetService("ReplicatedStorage")
local UserInputService = game:GetService("UserInputService")

local player = Players.LocalPlayer
local character = player.Character or player.CharacterAdded:Wait()
local humanoid = character:WaitForChild("Humanoid")

-- Wait for RemoteEvents
local remoteEventsFolder = ReplicatedStorage:WaitForChild("RemoteEvents")
local openShopEvent = remoteEventsFolder:WaitForChild("OpenShopEvent")
local openInventoryEvent = remoteEventsFolder:WaitForChild("OpenInventoryEvent")

-- Get locations from workspace
local locationsFolder = workspace:WaitForChild("FishingLocations")
local shopLocation = locationsFolder:WaitForChild("EquipmentShop")
local sellLocation = locationsFolder:WaitForChild("FishMarket")

-- Shop interaction
local shopInteraction = shopLocation:WaitForChild("ShopInteraction")
local shopClickDetector = shopInteraction:WaitForChild("ClickDetector")

-- Sell interaction
local sellInteraction = sellLocation:WaitForChild("SellInteraction")
local sellClickDetector = sellInteraction:WaitForChild("ClickDetector")

-- Proximity detection
local function isPlayerNearby(player, part)
    if not player.Character or not player.Character:FindFirstChild("HumanoidRootPart") then
        return false
    end
    
    local distance = (player.Character.HumanoidRootPart.Position - part.Position).Magnitude
    return distance <= 15 -- Within 15 studs
end

-- Show interaction prompt
local function showInteractionPrompt(text, position)
    local screenGui = player:WaitForChild("PlayerGui")
    local promptGui = screenGui:FindFirstChild("InteractionPrompt")
    
    if promptGui then
        promptGui:Destroy()
    end
    
    promptGui = Instance.new("ScreenGui")
    promptGui.Name = "InteractionPrompt"
    promptGui.Parent = screenGui
    
    local frame = Instance.new("Frame")
    frame.Size = UDim2.new(0, 200, 0, 50)
    frame.Position = UDim2.new(0.5, -100, 0.8, 0)
    frame.BackgroundColor3 = Color3.fromRGB(50, 50, 50)
    frame.BorderSizePixel = 0
    frame.Parent = promptGui
    
    local corner = Instance.new("UICorner")
    corner.CornerRadius = UDim.new(0, 10)
    corner.Parent = frame
    
    local label = Instance.new("TextLabel")
    label.Size = UDim2.new(1, 0, 1, 0)
    label.BackgroundTransparency = 1
    label.Text = text
    label.TextColor3 = Color3.fromRGB(255, 255, 255)
    label.TextScaled = true
    label.Font = Enum.Font.SourceSansBold
    label.Parent = frame
    
    -- Animate in
    frame.Size = UDim2.new(0, 0, 0, 0)
    local tween = game:GetService("TweenService"):Create(frame, TweenInfo.new(0.3), {Size = UDim2.new(0, 200, 0, 50)})
    tween:Play()
    
    return promptGui
end

-- Hide interaction prompt
local function hideInteractionPrompt()
    local screenGui = player:WaitForChild("PlayerGui")
    local promptGui = screenGui:FindFirstChild("InteractionPrompt")
    if promptGui then
        promptGui:Destroy()
    end
end

-- Shop interaction
shopClickDetector.MouseClick:Connect(function(clickingPlayer)
    if clickingPlayer == player then
        openShopEvent:FireClient(player)
    end
end)

-- Sell interaction
sellClickDetector.MouseClick:Connect(function(clickingPlayer)
    if clickingPlayer == player then
        openInventoryEvent:FireClient(player)
    end
end)

-- Proximity detection for shop
spawn(function()
    while true do
        wait(0.5)
        if isPlayerNearby(player, shopInteraction) then
            if not player.PlayerGui:FindFirstChild("InteractionPrompt") then
                showInteractionPrompt("Click to open Equipment Shop", shopInteraction.Position)
            end
        else
            hideInteractionPrompt()
        end
    end
end)

-- Proximity detection for sell location
spawn(function()
    while true do
        wait(0.5)
        if isPlayerNearby(player, sellInteraction) then
            if not player.PlayerGui:FindFirstChild("InteractionPrompt") then
                showInteractionPrompt("Click to open Fish Market", sellInteraction.Position)
            end
        end
    end
end)

-- Handle character respawning
player.CharacterAdded:Connect(function(newCharacter)
    character = newCharacter
    humanoid = character:WaitForChild("Humanoid")
end)

print("Location Interaction System initialized successfully!")