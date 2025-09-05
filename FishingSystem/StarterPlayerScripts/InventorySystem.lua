-- Inventory System for Fishing System
-- Handles fish inventory and selling interface

local Players = game:GetService("Players")
local ReplicatedStorage = game:GetService("ReplicatedStorage")
local TweenService = game:GetService("TweenService")

local player = Players.LocalPlayer
local playerGui = player:WaitForChild("PlayerGui")

-- Wait for RemoteEvents
local remoteEventsFolder = ReplicatedStorage:WaitForChild("RemoteEvents")
local sellFishEvent = remoteEventsFolder:WaitForChild("SellFishEvent")
local getPlayerDataFunction = remoteEventsFolder:WaitForChild("GetPlayerDataFunction")
local openInventoryEvent = remoteEventsFolder:WaitForChild("OpenInventoryEvent")

-- Create inventory GUI
local inventoryGui = Instance.new("ScreenGui")
inventoryGui.Name = "InventoryGui"
inventoryGui.Parent = playerGui
inventoryGui.Enabled = false

-- Main inventory frame
local inventoryFrame = Instance.new("Frame")
inventoryFrame.Name = "InventoryFrame"
inventoryFrame.Size = UDim2.new(0, 500, 0, 400)
inventoryFrame.Position = UDim2.new(0.5, -250, 0.5, -200)
inventoryFrame.BackgroundColor3 = Color3.fromRGB(50, 50, 50)
inventoryFrame.BorderSizePixel = 0
inventoryFrame.Parent = inventoryGui

local inventoryCorner = Instance.new("UICorner")
inventoryCorner.CornerRadius = UDim.new(0, 15)
inventoryCorner.Parent = inventoryFrame

-- Title
local inventoryTitle = Instance.new("TextLabel")
inventoryTitle.Name = "InventoryTitle"
inventoryTitle.Size = UDim2.new(1, 0, 0, 50)
inventoryTitle.Position = UDim2.new(0, 0, 0, 0)
inventoryTitle.BackgroundColor3 = Color3.fromRGB(70, 70, 70)
inventoryTitle.BorderSizePixel = 0
inventoryTitle.Text = "🎒 Fish Inventory"
inventoryTitle.TextColor3 = Color3.fromRGB(255, 255, 255)
inventoryTitle.TextScaled = true
inventoryTitle.Font = Enum.Font.SourceSansBold
inventoryTitle.Parent = inventoryFrame

local titleCorner = Instance.new("UICorner")
titleCorner.CornerRadius = UDim.new(0, 15)
titleCorner.Parent = inventoryTitle

-- Close button
local closeButton = Instance.new("TextButton")
closeButton.Name = "CloseButton"
closeButton.Size = UDim2.new(0, 40, 0, 40)
closeButton.Position = UDim2.new(1, -50, 0, 5)
closeButton.BackgroundColor3 = Color3.fromRGB(200, 50, 50)
closeButton.BorderSizePixel = 0
closeButton.Text = "✕"
closeButton.TextColor3 = Color3.fromRGB(255, 255, 255)
closeButton.TextScaled = true
closeButton.Font = Enum.Font.SourceSansBold
closeButton.Parent = inventoryTitle

local closeCorner = Instance.new("UICorner")
closeCorner.CornerRadius = UDim.new(0, 8)
closeCorner.Parent = closeButton

-- Player info frame
local playerInfoFrame = Instance.new("Frame")
playerInfoFrame.Name = "PlayerInfoFrame"
playerInfoFrame.Size = UDim2.new(1, -20, 0, 60)
playerInfoFrame.Position = UDim2.new(0, 10, 0, 60)
playerInfoFrame.BackgroundColor3 = Color3.fromRGB(60, 60, 60)
playerInfoFrame.BorderSizePixel = 0
playerInfoFrame.Parent = inventoryFrame

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

-- Total value label
local totalValueLabel = Instance.new("TextLabel")
totalValueLabel.Name = "TotalValueLabel"
totalValueLabel.Size = UDim2.new(0.5, -5, 0, 25)
totalValueLabel.Position = UDim2.new(0.5, 0, 0, 5)
totalValueLabel.BackgroundTransparency = 1
totalValueLabel.Text = "Total Value: $0"
totalValueLabel.TextColor3 = Color3.fromRGB(0, 255, 0)
totalValueLabel.TextScaled = true
totalValueLabel.Font = Enum.Font.SourceSansBold
totalValueLabel.Parent = playerInfoFrame

-- Sell all button
local sellAllButton = Instance.new("TextButton")
sellAllButton.Name = "SellAllButton"
sellAllButton.Size = UDim2.new(0.3, 0, 0, 25)
sellAllButton.Position = UDim2.new(0.35, 0, 0, 30)
sellAllButton.BackgroundColor3 = Color3.fromRGB(0, 200, 0)
sellAllButton.BorderSizePixel = 0
sellAllButton.Text = "💰 Sell All Fish"
sellAllButton.TextColor3 = Color3.fromRGB(255, 255, 255)
sellAllButton.TextScaled = true
sellAllButton.Font = Enum.Font.SourceSansBold
sellAllButton.Parent = playerInfoFrame

local sellAllCorner = Instance.new("UICorner")
sellAllCorner.CornerRadius = UDim.new(0, 6)
sellAllCorner.Parent = sellAllButton

-- Fish list frame
local fishListFrame = Instance.new("ScrollingFrame")
fishListFrame.Name = "FishListFrame"
fishListFrame.Size = UDim2.new(1, -20, 1, -140)
fishListFrame.Position = UDim2.new(0, 10, 0, 130)
fishListFrame.BackgroundColor3 = Color3.fromRGB(40, 40, 40)
fishListFrame.BorderSizePixel = 0
fishListFrame.ScrollBarThickness = 8
fishListFrame.Parent = inventoryFrame

local fishListCorner = Instance.new("UICorner")
fishListCorner.CornerRadius = UDim.new(0, 8)
fishListCorner.Parent = fishListFrame

-- Fish data (will be loaded from server)
local FishData = {
    ["Common Fish"] = {BasePrice = 10, Rarity = "Common"},
    ["Rare Fish"] = {BasePrice = 50, Rarity = "Rare"},
    ["Epic Fish"] = {BasePrice = 150, Rarity = "Epic"},
    ["Legendary Fish"] = {BasePrice = 500, Rarity = "Legendary"}
}

-- Get rarity color
local function getRarityColor(rarity)
    if rarity == "Common" then
        return Color3.fromRGB(200, 200, 200)
    elseif rarity == "Rare" then
        return Color3.fromRGB(0, 150, 255)
    elseif rarity == "Epic" then
        return Color3.fromRGB(150, 0, 255)
    elseif rarity == "Legendary" then
        return Color3.fromRGB(255, 150, 0)
    else
        return Color3.fromRGB(255, 255, 255)
    end
end

-- Load fish inventory
local function loadFishInventory()
    local playerData = getPlayerDataFunction:InvokeServer()
    if not playerData then return end
    
    -- Clear existing fish items
    for _, child in pairs(fishListFrame:GetChildren()) do
        if child:IsA("GuiObject") then
            child:Destroy()
        end
    end
    
    local yOffset = 10
    local itemHeight = 70
    local totalValue = 0
    
    -- Update player info
    moneyLabel.Text = "Money: $" .. playerData.Money
    
    -- Create fish items
    for fishName, fishCount in pairs(playerData.Inventory.Fish) do
        if fishCount > 0 then
            local fishData = FishData[fishName]
            if fishData then
                local fishValue = fishData.BasePrice * fishCount
                totalValue = totalValue + fishValue
                
                local itemFrame = Instance.new("Frame")
                itemFrame.Name = fishName .. "Item"
                itemFrame.Size = UDim2.new(1, -20, 0, itemHeight)
                itemFrame.Position = UDim2.new(0, 10, 0, yOffset)
                itemFrame.BackgroundColor3 = Color3.fromRGB(60, 60, 60)
                itemFrame.BorderSizePixel = 0
                itemFrame.Parent = fishListFrame
                
                local itemCorner = Instance.new("UICorner")
                itemCorner.CornerRadius = UDim.new(0, 8)
                itemCorner.Parent = itemFrame
                
                -- Fish name
                local nameLabel = Instance.new("TextLabel")
                nameLabel.Size = UDim2.new(0.4, 0, 0, 25)
                nameLabel.Position = UDim2.new(0, 10, 0, 5)
                nameLabel.BackgroundTransparency = 1
                nameLabel.Text = fishName
                nameLabel.TextColor3 = Color3.fromRGB(255, 255, 255)
                nameLabel.TextScaled = true
                nameLabel.Font = Enum.Font.SourceSansBold
                nameLabel.TextXAlignment = Enum.TextXAlignment.Left
                nameLabel.Parent = itemFrame
                
                -- Quantity
                local quantityLabel = Instance.new("TextLabel")
                quantityLabel.Size = UDim2.new(0.15, 0, 0, 25)
                quantityLabel.Position = UDim2.new(0.4, 0, 0, 5)
                quantityLabel.BackgroundTransparency = 1
                quantityLabel.Text = "x" .. fishCount
                quantityLabel.TextColor3 = Color3.fromRGB(100, 200, 100)
                quantityLabel.TextScaled = true
                quantityLabel.Font = Enum.Font.SourceSansBold
                quantityLabel.TextXAlignment = Enum.TextXAlignment.Center
                quantityLabel.Parent = itemFrame
                
                -- Price per fish
                local priceLabel = Instance.new("TextLabel")
                priceLabel.Size = UDim2.new(0.15, 0, 0, 25)
                priceLabel.Position = UDim2.new(0.55, 0, 0, 5)
                priceLabel.BackgroundTransparency = 1
                priceLabel.Text = "$" .. fishData.BasePrice
                priceLabel.TextColor3 = Color3.fromRGB(255, 215, 0)
                priceLabel.TextScaled = true
                priceLabel.Font = Enum.Font.SourceSansBold
                priceLabel.TextXAlignment = Enum.TextXAlignment.Center
                priceLabel.Parent = itemFrame
                
                -- Total value
                local totalLabel = Instance.new("TextLabel")
                totalLabel.Size = UDim2.new(0.15, 0, 0, 25)
                totalLabel.Position = UDim2.new(0.7, 0, 0, 5)
                totalLabel.BackgroundTransparency = 1
                totalLabel.Text = "$" .. fishValue
                totalLabel.TextColor3 = Color3.fromRGB(0, 255, 0)
                totalLabel.TextScaled = true
                totalLabel.Font = Enum.Font.SourceSansBold
                totalLabel.TextXAlignment = Enum.TextXAlignment.Center
                totalLabel.Parent = itemFrame
                
                -- Rarity
                local rarityLabel = Instance.new("TextLabel")
                rarityLabel.Size = UDim2.new(0.4, 0, 0, 20)
                rarityLabel.Position = UDim2.new(0, 10, 0, 30)
                rarityLabel.BackgroundTransparency = 1
                rarityLabel.Text = "Rarity: " .. fishData.Rarity
                rarityLabel.TextColor3 = getRarityColor(fishData.Rarity)
                rarityLabel.TextScaled = true
                rarityLabel.Font = Enum.Font.SourceSans
                rarityLabel.TextXAlignment = Enum.TextXAlignment.Left
                rarityLabel.Parent = itemFrame
                
                -- Sell all button
                local sellAllButton = Instance.new("TextButton")
                sellAllButton.Name = "SellAllButton"
                sellAllButton.Size = UDim2.new(0.12, 0, 0, 30)
                sellAllButton.Position = UDim2.new(0.55, 0, 0, 30)
                sellAllButton.BackgroundColor3 = Color3.fromRGB(0, 150, 0)
                sellAllButton.BorderSizePixel = 0
                sellAllButton.Text = "Sell All"
                sellAllButton.TextColor3 = Color3.fromRGB(255, 255, 255)
                sellAllButton.TextScaled = true
                sellAllButton.Font = Enum.Font.SourceSansBold
                sellAllButton.Parent = itemFrame
                
                local sellAllCorner = Instance.new("UICorner")
                sellAllCorner.CornerRadius = UDim.new(0, 6)
                sellAllCorner.Parent = sellAllButton
                
                sellAllButton.MouseButton1Click:Connect(function()
                    sellFishEvent:FireServer(fishName, fishCount)
                    wait(0.5) -- Wait for server response
                    loadFishInventory() -- Refresh
                end)
                
                -- Sell one button
                local sellOneButton = Instance.new("TextButton")
                sellOneButton.Name = "SellOneButton"
                sellOneButton.Size = UDim2.new(0.12, 0, 0, 30)
                sellOneButton.Position = UDim2.new(0.7, 0, 0, 30)
                sellOneButton.BackgroundColor3 = Color3.fromRGB(0, 100, 200)
                sellOneButton.BorderSizePixel = 0
                sellOneButton.Text = "Sell 1"
                sellOneButton.TextColor3 = Color3.fromRGB(255, 255, 255)
                sellOneButton.TextScaled = true
                sellOneButton.Font = Enum.Font.SourceSansBold
                sellOneButton.Parent = itemFrame
                
                local sellOneCorner = Instance.new("UICorner")
                sellOneCorner.CornerRadius = UDim.new(0, 6)
                sellOneCorner.Parent = sellOneButton
                
                sellOneButton.MouseButton1Click:Connect(function()
                    sellFishEvent:FireServer(fishName, 1)
                    wait(0.5) -- Wait for server response
                    loadFishInventory() -- Refresh
                end)
                
                yOffset = yOffset + itemHeight + 10
            end
        end
    end
    
    -- Update total value
    totalValueLabel.Text = "Total Value: $" .. totalValue
    
    -- Set canvas size
    fishListFrame.CanvasSize = UDim2.new(0, 0, 0, yOffset)
    
    -- Show message if no fish
    if yOffset == 10 then
        local noFishLabel = Instance.new("TextLabel")
        noFishLabel.Size = UDim2.new(1, -20, 0, 50)
        noFishLabel.Position = UDim2.new(0, 10, 0, 50)
        noFishLabel.BackgroundTransparency = 1
        noFishLabel.Text = "No fish in inventory!\nGo catch some fish first."
        noFishLabel.TextColor3 = Color3.fromRGB(200, 200, 200)
        noFishLabel.TextScaled = true
        noFishLabel.Font = Enum.Font.SourceSans
        noFishLabel.Parent = fishListFrame
    end
end

-- Sell all fish function
local function sellAllFish()
    local playerData = getPlayerDataFunction:InvokeServer()
    if not playerData then return end
    
    local totalEarned = 0
    local fishSold = {}
    
    for fishName, fishCount in pairs(playerData.Inventory.Fish) do
        if fishCount > 0 then
            local fishData = FishData[fishName]
            if fishData then
                local fishValue = fishData.BasePrice * fishCount
                totalEarned = totalEarned + fishValue
                table.insert(fishSold, {Name = fishName, Count = fishCount, Value = fishValue})
            end
        end
    end
    
    -- Sell all fish
    for _, fish in pairs(fishSold) do
        sellFishEvent:FireServer(fish.Name, fish.Count)
    end
    
    wait(1) -- Wait for all sales to complete
    loadFishInventory() -- Refresh
end

-- Button events
closeButton.MouseButton1Click:Connect(function()
    inventoryGui.Enabled = false
end)

sellAllButton.MouseButton1Click:Connect(function()
    sellAllFish()
end)

-- Open inventory event
openInventoryEvent.OnClientEvent:Connect(function()
    inventoryGui.Enabled = true
    loadFishInventory()
end)

-- Initialize
loadFishInventory()

print("Inventory System initialized successfully!")