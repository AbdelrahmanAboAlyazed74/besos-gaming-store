# This file is auto-generated from the current state of the database. Instead
# of editing this file, please use the migrations feature of Active Record to
# incrementally modify your database, and then regenerate this schema definition.

ActiveRecord::Schema.define(version: 2026_05_16_000000) do

  create_table "products", force: :cascade do |t|
    t.string "name", null: false
    t.string "category", null: false
    t.float "price", null: false
    t.text "description"
  end

  create_table "orders", force: :cascade do |t|
    t.string "customer_name", null: false
    t.string "phone", null: false
    t.string "address", null: false
    t.float "total_amount", null: false
    t.string "status", default: "Pending"
    t.datetime "created_at", default: -> { "CURRENT_TIMESTAMP" }
  end

  create_table "order_items", force: :cascade do |t|
    t.integer "order_id", null: false
    t.integer "product_id", null: false
    t.integer "quantity", null: false
    t.float "price", null: false
    t.index ["order_id"], name: "index_order_items_on_order_id"
    t.index ["product_id"], name: "index_order_items_on_product_id"
  end

  add_foreign_key "order_items", "orders"
  add_foreign_key "order_items", "products"
end

# Seed Data (All Products - Images Removed)
products_data = [
  { name: 'PS5 Pulse 3D Wireless Headset', category: 'PlayStation', price: 99.99, description: 'Enjoy a seamless, wireless experience with a headset fine-tuned for 3D Audio on PS5 consoles.' },
  { name: 'PS5 HD Camera', category: 'PlayStation', price: 59.99, description: 'Featuring dual lenses for 1080p capture and a built-in stand.' },
  { name: 'PS5 Media Remote', category: 'PlayStation', price: 29.99, description: 'Conveniently navigate entertainment on your PlayStation 5 console with intuitive media and TV controls.' },
  { name: 'PS VR2 Headset', category: 'PlayStation', price: 549.99, description: 'Escape into worlds that feel, look and sound truly real as virtual reality gaming takes a huge generational leap forward.' },
  { name: 'PS5 Console Covers (Midnight Black)', category: 'PlayStation', price: 54.99, description: 'Personalize your PS5 console with a vibrant array of colors.' },
  { name: 'PS5 DualSense Charging Station', category: 'PlayStation', price: 29.99, description: 'Charge up to two DualSense wireless controllers simultaneously without having to connect them to your PlayStation 5 console.' },
  { name: 'PlayStation Link USB Adapter', category: 'PlayStation', price: 24.99, description: 'Enjoy a lossless, lightning-fast wireless audio connection.' },
  { name: 'PS4 Gold Wireless Headset', category: 'PlayStation', price: 79.99, description: 'Discover how great your games can sound with the headset that offers extraordinary audio quality.' },
  
  { name: 'KontrolFreek FPS Freek Galaxy', category: 'Controller Accessories', price: 16.99, description: 'Performance Thumbsticks for PS5 and PS4 controllers.' },
  { name: 'eXtremeRate PlayVital Controller Grip', category: 'Controller Accessories', price: 14.99, description: 'Anti-slip silicone cover for PS5 controller.' },
  { name: 'SCUF Instinct Pro Performance Controller', category: 'Controller Accessories', price: 229.99, description: 'Custom performance controller designed for Xbox and PC.' },
  { name: 'Xbox Elite Wireless Controller Series 2', category: 'Controller Accessories', price: 129.99, description: 'Play like a pro with the world’s most advanced controller.' },
  { name: 'DualSense Edge Wireless Controller', category: 'Controller Accessories', price: 199.99, description: 'Get an edge in gameplay with customizable controls and swappable profiles.' },
  { name: 'Controller Silicone Skin Cover (Red)', category: 'Controller Accessories', price: 9.99, description: 'Protect your controller and improve your grip.' },
  { name: 'Thumb Grip Caps (Pack of 4)', category: 'Controller Accessories', price: 5.99, description: 'Enhance gaming experience with extra height and radius.' },
  
  { name: 'Razer DeathAdder V2 Gaming Mouse', category: 'PC Accessories', price: 69.99, description: 'Ergonomic mouse with best-in-class sensor.' },
  { name: 'Logitech G Pro X Mechanical Keyboard', category: 'PC Accessories', price: 149.99, description: 'Built for pros with swappable advanced mechanical switches.' },
  { name: 'SteelSeries QcK Gaming Mouse Pad', category: 'PC Accessories', price: 14.99, description: 'Legendary micro-woven cloth optimized for low and high DPI tracking movements.' },
  { name: 'HyperX Cloud II Gaming Headset', category: 'PC Accessories', price: 99.99, description: 'Comfortable gaming headset with 7.1 surround sound.' },
  { name: 'Elgato Stream Deck MK.2', category: 'PC Accessories', price: 149.99, description: 'Studio controller with 15 customizable LCD keys.' },
  { name: 'Blue Yeti USB Microphone', category: 'PC Accessories', price: 129.99, description: 'Professional multi-pattern USB mic for recording and streaming.' },
  { name: 'Razer Leviathan V2 X Soundbar', category: 'PC Accessories', price: 99.99, description: 'PC gaming soundbar with full-range drivers and Razer Chroma RGB.' },
  { name: 'Corsair MM700 RGB Extended Mouse Pad', category: 'PC Accessories', price: 59.99, description: 'Extended cloth gaming mouse pad with dynamic 360° RGB lighting.' },
  { name: 'ASUS ROG Swift 360Hz Gaming Monitor', category: 'PC Accessories', price: 699.99, description: '24.5-inch esports professional gaming monitor.' },

  { name: 'Xbox Wireless Headset', category: 'Xbox', price: 99.99, description: 'Experience high quality audio with a low-latency, 100% wireless connection to your Xbox console.' },
  { name: 'Seagate Storage Expansion Card (1TB)', category: 'Xbox', price: 219.99, description: 'Instantly expand the capacity of the most-powerful gaming experience Xbox has ever created.' },
  { name: 'Xbox Rechargeable Battery + USB-C', category: 'Xbox', price: 24.99, description: 'Keep the action going with the Xbox Rechargeable Battery + USB-C Cable.' },
  { name: 'Xbox Stereo Headset', category: 'Xbox', price: 59.99, description: 'Game loud and clear with the Xbox Stereo Headset.' },
  { name: 'Turtle Beach Stealth 600 Gen 2 MAX', category: 'Xbox', price: 119.99, description: 'Wireless multiplatform gaming headset for Xbox.' },
  { name: 'Xbox Adaptive Controller', category: 'Xbox', price: 99.99, description: 'Designed primarily to meet the needs of gamers with limited mobility.' }
]

# Assuming this code is copy-pasted to seeds.rb to seed the DB
products_data.each do |product|
  Product.create!(product)
end
