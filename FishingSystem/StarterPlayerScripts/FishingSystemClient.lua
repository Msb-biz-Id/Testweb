-- Client-side Fishing System
-- Handles UI, controls, and client-side fishing mechanics

local Players = game:GetService("Players")
local ReplicatedStorage = game:GetService("ReplicatedStorage")
local UserInputService = game:GetService("UserInputService")
local TweenService = game:GetService("TweenService")
local SoundService = game:GetService("SoundService")

local player = Players.LocalPlayer
local playerGui = player:WaitForChild("PlayerGui")

-- Wait for RemoteEvents
local remoteEventsFolder = ReplicatedStorage:WaitForChild("RemoteEvents")
local tryCatchFishEvent = remoteEventsFolder:WaitForChild("TryCatchFishEvent")
local buyEquipmentEvent = remoteEventsFolder:WaitForChild("BuyEquipmentEvent")
local sellFishEvent = remoteEventsFolder:WaitForChild("SellFishEvent")
local updateUIEvent = remoteEventsFolder:WaitForChild("UpdateUIEvent")
local openShopEvent = remoteEventsFolder:WaitForChild("OpenShopEvent")
local openInventoryEvent = remoteEventsFolder:WaitForChild("OpenInventoryEvent")
local rankUpEvent = remoteEventsFolder:WaitForChild("RankUpEvent")
local getPlayerDataFunction = remoteEventsFolder:WaitForChild("GetPlayerDataFunction")
local getShopDataFunction = remoteEventsFolder:WaitForChild("GetShopDataFunction")

-- Create main GUI
local fishingGui = Instance.new("ScreenGui")
fishingGui.Name = "FishingGui"
fishingGui.Parent = playerGui

-- Create main frame
local mainFrame = Instance.new("Frame")
mainFrame.Name = "MainFrame"
mainFrame.Size = UDim2.new(0, 400, 0, 300)
mainFrame.Position = UDim2.new(0, 20, 0, 20)
mainFrame.BackgroundColor3 = Color3.fromRGB(50, 50, 50)
mainFrame.BorderSizePixel = 0
mainFrame.Parent = fishingGui

-- Add corner radius
local corner = Instance.new("UICorner")
corner.CornerRadius = UDim.new(0, 10)
corner.Parent = mainFrame

-- Add title
local titleLabel = Instance.new("TextLabel")
titleLabel.Name = "TitleLabel"
titleLabel.Size = UDim2.new(1, 0, 0, 40)
titleLabel.Position = UDim2.new(0, 0, 0, 0)
titleLabel.BackgroundColor3 = Color3.fromRGB(70, 70, 70)
titleLabel.BorderSizePixel = 0
titleLabel.Text = "Fishing System"
titleLabel.TextColor3 = Color3.fromRGB(255, 255, 255)
titleLabel.TextScaled = true
titleLabel.Font = Enum.Font.SourceSansBold
titleLabel.Parent = mainFrame

-- Add corner radius to title
local titleCorner = Instance.new("UICorner")
titleCorner.CornerRadius = UDim.new(0, 10)
titleCorner.Parent = titleLabel

-- Player info frame
local playerInfoFrame = Instance.new("Frame")
playerInfoFrame.Name = "PlayerInfoFrame"
playerInfoFrame.Size = UDim2.new(1, -20, 0, 80)
playerInfoFrame.Position = UDim2.new(0, 10, 0, 50)
playerInfoFrame.BackgroundColor3 = Color3.fromRGB(60, 60, 60)
playerInfoFrame.BorderSizePixel = 0
playerInfoFrame.Parent = mainFrame

local playerInfoCorner = Instance.new("UICorner")
playerInfoCorner.CornerRadius = UDim.new(0, 8)
playerInfoCorner.Parent = playerInfoFrame

-- Money label
local moneyLabel = Instance.new("TextLabel")
moneyLabel.Name = "MoneyLabel"
moneyLabel.Size = UDim2.new(0.5, -5, 0, 25)
moneyLabel.Position = UDim2.new(0, 5, 0, 5)
moneyLabel.BackgroundTransparency = 1
moneyLabel.Text = "Money: $0"
moneyLabel.TextColor3 = Color3.fromRGB(255, 215, 0)
moneyLabel.TextScaled = true
moneyLabel.Font = Enum.Font.SourceSansBold
moneyLabel.Parent = playerInfoFrame

-- Rank label
local rankLabel = Instance.new("TextLabel")
rankLabel.Name = "RankLabel"
rankLabel.Size = UDim2.new(0.5, -5, 0, 25)
rankLabel.Position = UDim2.new(0.5, 0, 0, 5)
rankLabel.BackgroundTransparency = 1
rankLabel.Text = "Rank: Novice Fisher"
rankLabel.TextColor3 = Color3.fromRGB(255, 255, 255)
rankLabel.TextScaled = true
rankLabel.Font = Enum.Font.SourceSansBold
rankLabel.Parent = playerInfoFrame

-- Experience label
local expLabel = Instance.new("TextLabel")
expLabel.Name = "ExpLabel"
expLabel.Size = UDim2.new(1, -10, 0, 25)
expLabel.Position = UDim2.new(0, 5, 0, 30)
expLabel.BackgroundTransparency = 1
expLabel.Text = "Experience: 0/100"
expLabel.TextColor3 = Color3.fromRGB(100, 200, 255)
expLabel.TextScaled = true
expLabel.Font = Enum.Font.SourceSans
expLabel.Parent = playerInfoFrame

-- Equipment label
local equipmentLabel = Instance.new("TextLabel")
equipmentLabel.Name = "EquipmentLabel"
equipmentLabel.Size = UDim2.new(1, -10, 0, 25)
equipmentLabel.Position = UDim2.new(0, 5, 0, 55)
equipmentLabel.BackgroundTransparency = 1
equipmentLabel.Text = "Rod: Basic Rod | Bait: Worm"
equipmentLabel.TextColor3 = Color3.fromRGB(200, 200, 200)
equipmentLabel.TextScaled = true
equipmentLabel.Font = Enum.Font.SourceSans
equipmentLabel.Parent = playerInfoFrame

-- Fishing controls frame
local fishingControlsFrame = Instance.new("Frame")
fishingControlsFrame.Name = "FishingControlsFrame"
fishingControlsFrame.Size = UDim2.new(1, -20, 0, 100)
fishingControlsFrame.Position = UDim2.new(0, 10, 0, 140)
fishingControlsFrame.BackgroundColor3 = Color3.fromRGB(60, 60, 60)
fishingControlsFrame.BorderSizePixel = 0
fishingControlsFrame.Parent = mainFrame

local fishingControlsCorner = Instance.new("UICorner")
fishingControlsCorner.CornerRadius = UDim.new(0, 8)
fishingControlsCorner.Parent = fishingControlsFrame

-- Fish button
local fishButton = Instance.new("TextButton")
fishButton.Name = "FishButton"
fishButton.Size = UDim2.new(0.8, 0, 0, 40)
fishButton.Position = UDim2.new(0.1, 0, 0, 10)
fishButton.BackgroundColor3 = Color3.fromRGB(0, 150, 0)
fishButton.BorderSizePixel = 0
fishButton.Text = "🎣 Start Fishing"
fishButton.TextColor3 = Color3.fromRGB(255, 255, 255)
fishButton.TextScaled = true
fishButton.Font = Enum.Font.SourceSansBold
fishButton.Parent = fishingControlsFrame

local fishButtonCorner = Instance.new("UICorner")
fishButtonCorner.CornerRadius = UDim.new(0, 8)
fishButtonCorner.Parent = fishButton

-- Shop button
local shopButton = Instance.new("TextButton")
shopButton.Name = "ShopButton"
shopButton.Size = UDim2.new(0.35, 0, 0, 30)
shopButton.Position = UDim2.new(0.05, 0, 0, 60)
shopButton.BackgroundColor3 = Color3.fromRGB(0, 100, 200)
shopButton.BorderSizePixel = 0
shopButton.Text = "🛒 Shop"
shopButton.TextColor3 = Color3.fromRGB(255, 255, 255)
shopButton.TextScaled = true
shopButton.Font = Enum.Font.SourceSansBold
shopButton.Parent = fishingControlsFrame

local shopButtonCorner = Instance.new("UICorner")
shopButtonCorner.CornerRadius = UDim.new(0, 6)
shopButtonCorner.Parent = shopButton

-- Inventory button
local inventoryButton = Instance.new("TextButton")
inventoryButton.Name = "InventoryButton"
inventoryButton.Size = UDim2.new(0.35, 0, 0, 30)
inventoryButton.Position = UDim2.new(0.6, 0, 0, 60)
inventoryButton.BackgroundColor3 = Color3.fromRGB(150, 100, 0)
shopButton.BorderSizePixel = 0
inventoryButton.Text = "🎒 Inventory"
inventoryButton.TextColor3 = Color3.fromRGB(255, 255, 255)
inventoryButton.TextScaled = true
inventoryButton.Font = Enum.Font.SourceSansBold
inventoryButton.Parent = fishingControlsFrame

local inventoryButtonCorner = Instance.new("UICorner")
inventoryButtonCorner.CornerRadius = UDim.new(0, 6)
inventoryButtonCorner.Parent = inventoryButton

-- Status label
local statusLabel = Instance.new("TextLabel")
statusLabel.Name = "StatusLabel"
statusLabel.Size = UDim2.new(1, -20, 0, 40)
statusLabel.Position = UDim2.new(0, 10, 0, 250)
statusLabel.BackgroundTransparency = 1
statusLabel.Text = "Ready to fish!"
statusLabel.TextColor3 = Color3.fromRGB(255, 255, 255)
statusLabel.TextScaled = true
statusLabel.Font = Enum.Font.SourceSans
statusLabel.Parent = mainFrame

-- Fishing state
local isFishing = false

-- Fish button click handler
fishButton.MouseButton1Click:Connect(function()
    if not isFishing then
        tryCatchFishEvent:FireServer()
    end
end)

-- Shop button click handler
shopButton.MouseButton1Click:Connect(function()
    openShopEvent:FireClient(player)
end)

-- Inventory button click handler
inventoryButton.MouseButton1Click:Connect(function()
    openInventoryEvent:FireClient(player)
end)

-- Handle fishing started
local fishingStartedEvent = remoteEventsFolder:FindFirstChild("FishingStartedEvent")
if fishingStartedEvent then
    fishingStartedEvent.OnClientEvent:Connect(function()
        isFishing = true
        fishButton.Text = "🎣 Fishing..."
        fishButton.BackgroundColor3 = Color3.fromRGB(200, 100, 0)
        statusLabel.Text = "Fishing in progress..."
    end)
end

-- Handle fishing stopped
local fishingStoppedEvent = remoteEventsFolder:FindFirstChild("FishingStoppedEvent")
if fishingStoppedEvent then
    fishingStoppedEvent.OnClientEvent:Connect(function()
        isFishing = false
        fishButton.Text = "🎣 Start Fishing"
        fishButton.BackgroundColor3 = Color3.fromRGB(0, 150, 0)
    end)
end

-- Handle fish caught
local fishCaughtEvent = remoteEventsFolder:FindFirstChild("FishCaughtEvent")
if fishCaughtEvent then
    fishCaughtEvent.OnClientEvent:Connect(function(success, result, fishData)
        if success then
            statusLabel.Text = "Caught: " .. result .. "! +" .. fishData.Experience .. " XP"
            statusLabel.TextColor3 = Color3.fromRGB(0, 255, 0)
        else
            statusLabel.Text = "No fish caught this time..."
            statusLabel.TextColor3 = Color3.fromRGB(255, 100, 100)
        end
        
        -- Reset status after 3 seconds
        wait(3)
        statusLabel.Text = "Ready to fish!"
        statusLabel.TextColor3 = Color3.fromRGB(255, 255, 255)
    end)
end

-- Handle UI updates
updateUIEvent.OnClientEvent:Connect(function(playerData)
    moneyLabel.Text = "Money: $" .. playerData.Money
    rankLabel.Text = "Rank: " .. playerData.Rank
    expLabel.Text = "Experience: " .. playerData.Experience .. "/" .. (playerData.Rank * 100)
    equipmentLabel.Text = "Rod: " .. playerData.Equipment.Rod .. " | Bait: " .. playerData.Equipment.Bait
end)

-- Handle rank up
rankUpEvent.OnClientEvent:Connect(function(rankInfo)
    statusLabel.Text = "RANK UP! " .. rankInfo.Name
    statusLabel.TextColor3 = rankInfo.Color
    
    -- Create rank up effect
    local rankUpFrame = Instance.new("Frame")
    rankUpFrame.Size = UDim2.new(1, 0, 1, 0)
    rankUpFrame.Position = UDim2.new(0, 0, 0, 0)
    rankUpFrame.BackgroundColor3 = rankInfo.Color
    rankUpFrame.BackgroundTransparency = 0.7
    rankUpFrame.Parent = fishingGui
    
    local rankUpLabel = Instance.new("TextLabel")
    rankUpLabel.Size = UDim2.new(1, 0, 0, 100)
    rankUpLabel.Position = UDim2.new(0, 0, 0.5, -50)
    rankUpLabel.BackgroundTransparency = 1
    rankUpLabel.Text = "RANK UP!\n" .. rankInfo.Name
    rankUpLabel.TextColor3 = Color3.fromRGB(255, 255, 255)
    rankUpLabel.TextScaled = true
    rankUpLabel.Font = Enum.Font.SourceSansBold
    rankUpLabel.Parent = rankUpFrame
    
    -- Animate rank up effect
    local tween = TweenService:Create(rankUpFrame, TweenInfo.new(3, Enum.EasingStyle.Quad), {BackgroundTransparency = 1})
    tween:Play()
    
    tween.Completed:Connect(function()
        rankUpFrame:Destroy()
    end)
end)

-- Initialize UI with current player data
spawn(function()
    wait(2) -- Wait for server to initialize
    local playerData = getPlayerDataFunction:InvokeServer()
    if playerData then
        updateUIEvent:FireClient(player, playerData)
    end
end)

print("Fishing System Client initialized successfully!")