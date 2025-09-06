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
local moneyChangeEvent = remoteEventsFolder:WaitForChild("MoneyChangeEvent")
local giftReceivedEvent = remoteEventsFolder:WaitForChild("GiftReceivedEvent")
local giftMoneyResponseEvent = remoteEventsFolder:WaitForChild("GiftMoneyResponseEvent")

-- Create main GUI
local fishingGui = Instance.new("ScreenGui")
fishingGui.Name = "FishingGui"
fishingGui.Parent = playerGui

-- Create money display GUI (top-left corner)
local moneyGui = Instance.new("ScreenGui")
moneyGui.Name = "MoneyGui"
moneyGui.Parent = playerGui

-- Money display frame
local moneyFrame = Instance.new("Frame")
moneyFrame.Name = "MoneyFrame"
moneyFrame.Size = UDim2.new(0, 200, 0, 60)
moneyFrame.Position = UDim2.new(0, 20, 0, 20)
moneyFrame.BackgroundColor3 = Color3.fromRGB(0, 0, 0)
moneyFrame.BackgroundTransparency = 0.3
moneyFrame.BorderSizePixel = 0
moneyFrame.Parent = moneyGui

local moneyCorner = Instance.new("UICorner")
moneyCorner.CornerRadius = UDim.new(0, 10)
moneyCorner.Parent = moneyFrame

-- Money icon
local moneyIcon = Instance.new("TextLabel")
moneyIcon.Name = "MoneyIcon"
moneyIcon.Size = UDim2.new(0, 40, 1, 0)
moneyIcon.Position = UDim2.new(0, 5, 0, 0)
moneyIcon.BackgroundTransparency = 1
moneyIcon.Text = "💰"
moneyIcon.TextColor3 = Color3.fromRGB(255, 215, 0)
moneyIcon.TextScaled = true
moneyIcon.Font = Enum.Font.SourceSansBold
moneyIcon.Parent = moneyFrame

-- Money amount display
local moneyDisplay = Instance.new("TextLabel")
moneyDisplay.Name = "MoneyDisplay"
moneyDisplay.Size = UDim2.new(1, -50, 0, 30)
moneyDisplay.Position = UDim2.new(0, 50, 0, 5)
moneyDisplay.BackgroundTransparency = 1
moneyDisplay.Text = "$0"
moneyDisplay.TextColor3 = Color3.fromRGB(255, 215, 0)
moneyDisplay.TextScaled = true
moneyDisplay.Font = Enum.Font.SourceSansBold
moneyDisplay.TextXAlignment = Enum.TextXAlignment.Left
moneyDisplay.Parent = moneyFrame

-- Money change indicator
local moneyChange = Instance.new("TextLabel")
moneyChange.Name = "MoneyChange"
moneyChange.Size = UDim2.new(1, -50, 0, 20)
moneyChange.Position = UDim2.new(0, 50, 0, 35)
moneyChange.BackgroundTransparency = 1
moneyChange.Text = ""
moneyChange.TextColor3 = Color3.fromRGB(0, 255, 0)
moneyChange.TextScaled = true
moneyChange.Font = Enum.Font.SourceSans
moneyChange.TextXAlignment = Enum.TextXAlignment.Left
moneyChange.Parent = moneyFrame

-- Gift button
local giftButton = Instance.new("TextButton")
giftButton.Name = "GiftButton"
giftButton.Size = UDim2.new(0, 30, 0, 30)
giftButton.Position = UDim2.new(1, -35, 0, 15)
giftButton.BackgroundColor3 = Color3.fromRGB(0, 150, 0)
giftButton.BorderSizePixel = 0
giftButton.Text = "🎁"
giftButton.TextColor3 = Color3.fromRGB(255, 255, 255)
giftButton.TextScaled = true
giftButton.Font = Enum.Font.SourceSansBold
giftButton.Parent = moneyFrame

local giftCorner = Instance.new("UICorner")
giftCorner.CornerRadius = UDim.new(0, 6)
giftCorner.Parent = giftButton

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
titleLabel.Text = "Sistem Mancing"
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
moneyLabel.Text = "Uang: $0"
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
rankLabel.Text = "Level: Pemula"
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
expLabel.Text = "XP: 0/100"
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
equipmentLabel.Text = "Pancingan: Basic Rod | Umpan: Worm"
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
fishButton.Text = "🎣 Mulai Mancing"
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
shopButton.Text = "🛒 Toko"
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
inventoryButton.BorderSizePixel = 0
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
statusLabel.Text = "Siap memancing!"
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

-- Also allow fishing by clicking on water parts (handled by server)

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
        fishButton.Text = "🎣 Memancing..."
        fishButton.BackgroundColor3 = Color3.fromRGB(200, 100, 0)
        statusLabel.Text = "Sedang memancing..."
    end)
end

-- Handle fishing stopped
local fishingStoppedEvent = remoteEventsFolder:FindFirstChild("FishingStoppedEvent")
if fishingStoppedEvent then
    fishingStoppedEvent.OnClientEvent:Connect(function()
        isFishing = false
        fishButton.Text = "🎣 Mulai Mancing"
        fishButton.BackgroundColor3 = Color3.fromRGB(0, 150, 0)
    end)
end

-- Handle fish caught
local fishCaughtEvent = remoteEventsFolder:FindFirstChild("FishCaughtEvent")
if fishCaughtEvent then
    fishCaughtEvent.OnClientEvent:Connect(function(success, result, fishData)
        if success then
            statusLabel.Text = "Tangkap: " .. result .. "! +" .. fishData.Experience .. " XP"
            statusLabel.TextColor3 = Color3.fromRGB(0, 255, 0)
        else
            statusLabel.Text = "Tidak ada ikan yang tertangkap..."
            statusLabel.TextColor3 = Color3.fromRGB(255, 100, 100)
        end
        
        -- Reset status after 3 seconds
        wait(3)
        statusLabel.Text = "Siap memancing!"
        statusLabel.TextColor3 = Color3.fromRGB(255, 255, 255)
    end)
end

-- Handle UI updates
updateUIEvent.OnClientEvent:Connect(function(playerData)
    moneyLabel.Text = "Uang: $" .. playerData.Money
    rankLabel.Text = "Level: " .. playerData.Rank
    expLabel.Text = "XP: " .. playerData.Experience .. "/" .. (playerData.Rank * 100)
    equipmentLabel.Text = "Pancingan: " .. playerData.Equipment.Rod .. " | Umpan: " .. playerData.Equipment.Bait
    
    -- Update money display
    moneyDisplay.Text = "$" .. playerData.Money
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

-- Money change animation
local function showMoneyChange(amount, isPositive)
    if amount == 0 then return end
    
    local changeText = isPositive and "+$" .. amount or "-$" .. math.abs(amount)
    local changeColor = isPositive and Color3.fromRGB(0, 255, 0) or Color3.fromRGB(255, 100, 100)
    
    moneyChange.Text = changeText
    moneyChange.TextColor3 = changeColor
    
    -- Animate the change
    local tween = TweenService:Create(moneyChange, TweenInfo.new(0.5, Enum.EasingStyle.Quad), {TextTransparency = 1})
    tween:Play()
    
    tween.Completed:Connect(function()
        moneyChange.Text = ""
        moneyChange.TextTransparency = 0
    end)
end

-- Gift button functionality
giftButton.MouseButton1Click:Connect(function()
    -- Create gift GUI
    local giftGui = Instance.new("ScreenGui")
    giftGui.Name = "GiftGui"
    giftGui.Parent = playerGui
    
    local giftFrame = Instance.new("Frame")
    giftFrame.Size = UDim2.new(0, 300, 0, 200)
    giftFrame.Position = UDim2.new(0.5, -150, 0.5, -100)
    giftFrame.BackgroundColor3 = Color3.fromRGB(50, 50, 50)
    giftFrame.BorderSizePixel = 0
    giftFrame.Parent = giftGui
    
    local giftCorner = Instance.new("UICorner")
    giftCorner.CornerRadius = UDim.new(0, 10)
    giftCorner.Parent = giftFrame
    
    -- Title
    local giftTitle = Instance.new("TextLabel")
    giftTitle.Size = UDim2.new(1, 0, 0, 40)
    giftTitle.Position = UDim2.new(0, 0, 0, 0)
    giftTitle.BackgroundColor3 = Color3.fromRGB(70, 70, 70)
    giftTitle.BorderSizePixel = 0
    giftTitle.Text = "🎁 Kirim Hadiah Uang"
    giftTitle.TextColor3 = Color3.fromRGB(255, 255, 255)
    giftTitle.TextScaled = true
    giftTitle.Font = Enum.Font.SourceSansBold
    giftTitle.Parent = giftFrame
    
    local titleCorner = Instance.new("UICorner")
    titleCorner.CornerRadius = UDim.new(0, 10)
    titleCorner.Parent = giftTitle
    
    -- Close button
    local closeButton = Instance.new("TextButton")
    closeButton.Size = UDim2.new(0, 30, 0, 30)
    closeButton.Position = UDim2.new(1, -35, 0, 5)
    closeButton.BackgroundColor3 = Color3.fromRGB(200, 50, 50)
    closeButton.BorderSizePixel = 0
    closeButton.Text = "✕"
    closeButton.TextColor3 = Color3.fromRGB(255, 255, 255)
    closeButton.TextScaled = true
    closeButton.Font = Enum.Font.SourceSansBold
    closeButton.Parent = giftTitle
    
    local closeCorner = Instance.new("UICorner")
    closeCorner.CornerRadius = UDim.new(0, 6)
    closeCorner.Parent = closeButton
    
    -- Player name input
    local playerNameLabel = Instance.new("TextLabel")
    playerNameLabel.Size = UDim2.new(1, -20, 0, 25)
    playerNameLabel.Position = UDim2.new(0, 10, 0, 50)
    playerNameLabel.BackgroundTransparency = 1
    playerNameLabel.Text = "Nama Pemain:"
    playerNameLabel.TextColor3 = Color3.fromRGB(255, 255, 255)
    playerNameLabel.TextScaled = true
    playerNameLabel.Font = Enum.Font.SourceSans
    playerNameLabel.TextXAlignment = Enum.TextXAlignment.Left
    playerNameLabel.Parent = giftFrame
    
    local playerNameBox = Instance.new("TextBox")
    playerNameBox.Size = UDim2.new(1, -20, 0, 30)
    playerNameBox.Position = UDim2.new(0, 10, 0, 75)
    playerNameBox.BackgroundColor3 = Color3.fromRGB(60, 60, 60)
    playerNameBox.BorderSizePixel = 0
    playerNameBox.Text = ""
    playerNameBox.PlaceholderText = "Masukkan nama pemain..."
    playerNameBox.TextColor3 = Color3.fromRGB(255, 255, 255)
    playerNameBox.TextScaled = true
    playerNameBox.Font = Enum.Font.SourceSans
    playerNameBox.Parent = giftFrame
    
    local nameBoxCorner = Instance.new("UICorner")
    nameBoxCorner.CornerRadius = UDim.new(0, 6)
    nameBoxCorner.Parent = playerNameBox
    
    -- Amount input
    local amountLabel = Instance.new("TextLabel")
    amountLabel.Size = UDim2.new(1, -20, 0, 25)
    amountLabel.Position = UDim2.new(0, 10, 0, 110)
    amountLabel.BackgroundTransparency = 1
    amountLabel.Text = "Jumlah:"
    amountLabel.TextColor3 = Color3.fromRGB(255, 255, 255)
    amountLabel.TextScaled = true
    amountLabel.Font = Enum.Font.SourceSans
    amountLabel.TextXAlignment = Enum.TextXAlignment.Left
    amountLabel.Parent = giftFrame
    
    local amountBox = Instance.new("TextBox")
    amountBox.Size = UDim2.new(1, -20, 0, 30)
    amountBox.Position = UDim2.new(0, 10, 0, 135)
    amountBox.BackgroundColor3 = Color3.fromRGB(60, 60, 60)
    amountBox.BorderSizePixel = 0
    amountBox.Text = ""
    amountBox.PlaceholderText = "Masukkan jumlah..."
    amountBox.TextColor3 = Color3.fromRGB(255, 255, 255)
    amountBox.TextScaled = true
    amountBox.Font = Enum.Font.SourceSans
    amountBox.Parent = giftFrame
    
    local amountBoxCorner = Instance.new("UICorner")
    amountBoxCorner.CornerRadius = UDim.new(0, 6)
    amountBoxCorner.Parent = amountBox
    
    -- Gift button
    local sendGiftButton = Instance.new("TextButton")
    sendGiftButton.Size = UDim2.new(0.6, 0, 0, 30)
    sendGiftButton.Position = UDim2.new(0.2, 0, 0, 170)
    sendGiftButton.BackgroundColor3 = Color3.fromRGB(0, 150, 0)
    sendGiftButton.BorderSizePixel = 0
    sendGiftButton.Text = "Kirim Hadiah"
    sendGiftButton.TextColor3 = Color3.fromRGB(255, 255, 255)
    sendGiftButton.TextScaled = true
    sendGiftButton.Font = Enum.Font.SourceSansBold
    sendGiftButton.Parent = giftFrame
    
    local sendGiftCorner = Instance.new("UICorner")
    sendGiftCorner.CornerRadius = UDim.new(0, 6)
    sendGiftCorner.Parent = sendGiftButton
    
    -- Button events
    closeButton.MouseButton1Click:Connect(function()
        giftGui:Destroy()
    end)
    
    sendGiftButton.MouseButton1Click:Connect(function()
        local targetPlayer = playerNameBox.Text
        local amount = tonumber(amountBox.Text)
        
        if targetPlayer and amount and amount > 0 then
            -- Fire gift event to server
            local giftEvent = remoteEventsFolder:FindFirstChild("GiftMoneyEvent")
            if giftEvent then
                giftEvent:FireServer(targetPlayer, amount)
            end
            giftGui:Destroy()
        end
    end)
end)

-- Handle money change events
moneyChangeEvent.OnClientEvent:Connect(function(amount, isPositive)
    showMoneyChange(amount, isPositive)
end)

-- Handle gift received events
giftReceivedEvent.OnClientEvent:Connect(function(fromPlayerName, amount)
    -- Create gift received notification
    local notificationGui = Instance.new("ScreenGui")
    notificationGui.Name = "GiftNotification"
    notificationGui.Parent = playerGui
    
    local notificationFrame = Instance.new("Frame")
    notificationFrame.Size = UDim2.new(0, 300, 0, 80)
    notificationFrame.Position = UDim2.new(0.5, -150, 0.2, 0)
    notificationFrame.BackgroundColor3 = Color3.fromRGB(0, 150, 0)
    notificationFrame.BorderSizePixel = 0
    notificationFrame.Parent = notificationGui
    
    local notificationCorner = Instance.new("UICorner")
    notificationCorner.CornerRadius = UDim.new(0, 10)
    notificationCorner.Parent = notificationFrame
    
    local notificationLabel = Instance.new("TextLabel")
    notificationLabel.Size = UDim2.new(1, 0, 1, 0)
    notificationLabel.BackgroundTransparency = 1
    notificationLabel.Text = "🎁 Hadiah Diterima!\n" .. fromPlayerName .. " mengirim $" .. amount
    notificationLabel.TextColor3 = Color3.fromRGB(255, 255, 255)
    notificationLabel.TextScaled = true
    notificationLabel.Font = Enum.Font.SourceSansBold
    notificationLabel.Parent = notificationFrame
    
    -- Animate notification
    local tween = TweenService:Create(notificationFrame, TweenInfo.new(3, Enum.EasingStyle.Quad), {BackgroundTransparency = 1})
    tween:Play()
    
    tween.Completed:Connect(function()
        notificationGui:Destroy()
    end)
end)

-- Handle gift response events
giftMoneyResponseEvent.OnClientEvent:Connect(function(success, message)
    -- Create response notification
    local responseGui = Instance.new("ScreenGui")
    responseGui.Name = "GiftResponse"
    responseGui.Parent = playerGui
    
    local responseFrame = Instance.new("Frame")
    responseFrame.Size = UDim2.new(0, 250, 0, 60)
    responseFrame.Position = UDim2.new(0.5, -125, 0.3, 0)
    responseFrame.BackgroundColor3 = success and Color3.fromRGB(0, 150, 0) or Color3.fromRGB(200, 50, 50)
    responseFrame.BorderSizePixel = 0
    responseFrame.Parent = responseGui
    
    local responseCorner = Instance.new("UICorner")
    responseCorner.CornerRadius = UDim.new(0, 8)
    responseCorner.Parent = responseFrame
    
    local responseLabel = Instance.new("TextLabel")
    responseLabel.Size = UDim2.new(1, 0, 1, 0)
    responseLabel.BackgroundTransparency = 1
    responseLabel.Text = message
    responseLabel.TextColor3 = Color3.fromRGB(255, 255, 255)
    responseLabel.TextScaled = true
    responseLabel.Font = Enum.Font.SourceSansBold
    responseLabel.Parent = responseFrame
    
    -- Animate response
    local tween = TweenService:Create(responseFrame, TweenInfo.new(2, Enum.EasingStyle.Quad), {BackgroundTransparency = 1})
    tween:Play()
    
    tween.Completed:Connect(function()
        responseGui:Destroy()
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