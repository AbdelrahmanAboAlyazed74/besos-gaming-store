import React, { useState, useEffect } from 'react';
import { Gamepad2, ShoppingCart, Plus, Minus, Trash2, X, CheckCircle } from 'lucide-react';
import './App.css';

const API_URL = 'http://localhost/backend/api.php';

const CATEGORIES = ['All', 'PlayStation', 'Controller Accessories', 'PC Accessories', 'Xbox'];

function App() {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [activeCategory, setActiveCategory] = useState('All');
  
  // Cart State
  const [cart, setCart] = useState([]);
  const [isCartOpen, setIsCartOpen] = useState(false);
  const [checkoutStep, setCheckoutStep] = useState(0); // 0: Cart, 1: Checkout Form, 2: Success
  const [checkoutData, setCheckoutData] = useState({
    name: '',
    phone: '',
    address: ''
  });

  useEffect(() => {
    fetchProducts();
  }, []);

  const fetchProducts = async () => {
    try {
      setLoading(true);
      const response = await fetch(API_URL);
      if (!response.ok) throw new Error('Failed to fetch from API.');
      const data = await response.json();
      setProducts(data);
      setError(null);
    } catch (err) {
      setError(err.message);
      setProducts([]);
    } finally {
      setLoading(false);
    }
  };

  const addToCart = (product) => {
    setCart(prev => {
      const existing = prev.find(item => item.id === product.id);
      if (existing) {
        return prev.map(item => item.id === product.id ? { ...item, quantity: item.quantity + 1 } : item);
      }
      return [...prev, { ...product, quantity: 1 }];
    });
  };

  const updateQuantity = (id, amount) => {
    setCart(prev => {
      return prev.map(item => {
        if (item.id === id) {
          const newQuantity = item.quantity + amount;
          return newQuantity > 0 ? { ...item, quantity: newQuantity } : item;
        }
        return item;
      });
    });
  };

  const removeFromCart = (id) => {
    setCart(prev => prev.filter(item => item.id !== id));
  };

  const cartTotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
  const cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);

  const handleCheckoutChange = (e) => {
    const { name, value } = e.target;
    setCheckoutData({ ...checkoutData, [name]: value });
  };

  const submitOrder = async (e) => {
    e.preventDefault();
    
    const orderPayload = {
      customer_name: checkoutData.name,
      phone: checkoutData.phone,
      address: checkoutData.address,
      total_amount: cartTotal,
      items: cart.map(item => ({
        id: item.id,
        quantity: item.quantity,
        price: item.price
      }))
    };

    try {
      const response = await fetch(API_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(orderPayload),
      });

      if (!response.ok) throw new Error('Failed to place order.');
      
      setCheckoutStep(2);
      setCart([]);
    } catch (err) {
      alert('Error placing order: ' + err.message);
    }
  };

  const filteredProducts = activeCategory === 'All' 
    ? products 
    : products.filter(p => p.category === activeCategory);

  return (
    <div className="app-container">
      <header>
        <div className="logo">
          <Gamepad2 size={32} />
          Besos Gaming
        </div>
        <div className="header-actions">
          <button className="btn btn-primary cart-btn" onClick={() => { setIsCartOpen(true); setCheckoutStep(0); }}>
            <ShoppingCart size={18} />
            Cart
            {cartCount > 0 && <span className="cart-badge">{cartCount}</span>}
          </button>
        </div>
      </header>

      {error && (
        <div className="error-message">
          <strong>Connection Error:</strong> {error}
        </div>
      )}

      <div className="filters">
        {CATEGORIES.map(cat => (
          <button 
            key={cat} 
            className={`filter-btn ${activeCategory === cat ? 'active' : ''}`}
            onClick={() => setActiveCategory(cat)}
          >
            {cat}
          </button>
        ))}
      </div>

      {loading ? (
        <div className="loading">Loading Arsenal...</div>
      ) : (
        <div className="products-grid">
          {filteredProducts.map(product => (
            <div key={product.id} className="product-card">
              <img src={product.image || 'https://via.placeholder.com/400x300?text=No+Image'} alt={product.name} className="product-image" />
              <div className="product-info">
                <div className="product-category">{product.category}</div>
                <h3 className="product-title">{product.name}</h3>
                <p className="product-desc">{product.description}</p>
                <div className="product-price">${Number(product.price).toFixed(2)}</div>
                
                <div className="product-actions">
                  <button className="btn btn-primary" onClick={() => addToCart(product)} style={{flex: 1}}>
                    <ShoppingCart size={16} /> Add to Cart
                  </button>
                </div>
              </div>
            </div>
          ))}
          {!loading && filteredProducts.length === 0 && !error && (
            <div style={{gridColumn: '1 / -1', textAlign: 'center', padding: '40px', color: 'var(--text-muted)'}}>
              No products found in this category.
            </div>
          )}
        </div>
      )}

      {/* Cart & Checkout Drawer */}
      {isCartOpen && (
        <>
          <div className="cart-overlay" onClick={() => setIsCartOpen(false)}></div>
          <div className="cart-drawer">
            <div className="cart-header">
              <h2>
                {checkoutStep === 0 && <><ShoppingCart /> Your Cart</>}
                {checkoutStep === 1 && 'Checkout Details'}
                {checkoutStep === 2 && 'Order Completed'}
              </h2>
              <button onClick={() => setIsCartOpen(false)} style={{background: 'none', border: 'none', color: 'var(--text-muted)', cursor: 'pointer'}}>
                <X size={24} />
              </button>
            </div>
            
            {checkoutStep === 0 && (
              <>
                <div className="cart-body">
                  {cart.length === 0 ? (
                    <div className="empty-cart">
                      <ShoppingCart size={48} opacity={0.5} />
                      <p>Your cart is empty.</p>
                    </div>
                  ) : (
                    <div className="cart-items">
                      {cart.map(item => (
                        <div key={item.id} className="cart-item">
                          <img src={item.image} alt={item.name} className="cart-item-img" />
                          <div className="cart-item-info">
                            <h4>{item.name}</h4>
                            <p className="cart-item-price">${Number(item.price).toFixed(2)}</p>
                          </div>
                          <div className="cart-item-controls">
                            <button onClick={() => updateQuantity(item.id, -1)}><Minus size={14} /></button>
                            <span>{item.quantity}</span>
                            <button onClick={() => updateQuantity(item.id, 1)}><Plus size={14} /></button>
                          </div>
                          <button className="remove-btn" onClick={() => removeFromCart(item.id)}><Trash2 size={18} /></button>
                        </div>
                      ))}
                    </div>
                  )}
                </div>
                {cart.length > 0 && (
                  <div className="cart-footer">
                    <div className="cart-total">
                      <span>Total:</span>
                      <span>${cartTotal.toFixed(2)}</span>
                    </div>
                    <button className="btn btn-primary full-width" onClick={() => setCheckoutStep(1)}>
                      Proceed to Checkout
                    </button>
                  </div>
                )}
              </>
            )}

            {checkoutStep === 1 && (
              <>
                <div className="cart-body">
                  <form id="checkoutForm" onSubmit={submitOrder} className="checkout-form">
                    <div className="form-group">
                      <label>Full Name</label>
                      <input type="text" name="name" value={checkoutData.name} onChange={handleCheckoutChange} required placeholder="Enter your name" />
                    </div>
                    <div className="form-group">
                      <label>Phone Number</label>
                      <input type="tel" name="phone" value={checkoutData.phone} onChange={handleCheckoutChange} required placeholder="01xxxxxxxxx" />
                    </div>
                    <div className="form-group">
                      <label>Delivery Address</label>
                      <textarea name="address" value={checkoutData.address} onChange={handleCheckoutChange} required rows="3" placeholder="City, Street, Building, Apartment..."></textarea>
                    </div>
                    <div className="form-group">
                      <label>Payment Method</label>
                      <div className="payment-method-box">
                        <span className="cash-badge">Cash on Delivery</span>
                        <p>You will pay when the order arrives.</p>
                      </div>
                    </div>
                  </form>
                </div>
                <div className="cart-footer">
                  <div className="cart-total">
                    <span>Amount to Pay:</span>
                    <span>${cartTotal.toFixed(2)}</span>
                  </div>
                  <div className="form-actions" style={{marginTop: 0}}>
                    <button type="button" className="btn" onClick={() => setCheckoutStep(0)}>Back</button>
                    <button type="submit" form="checkoutForm" className="btn btn-primary">Confirm Order</button>
                  </div>
                </div>
              </>
            )}

            {checkoutStep === 2 && (
              <div className="cart-body" style={{justifyContent: 'center'}}>
                <div className="success-message">
                  <CheckCircle size={64} color="var(--primary-color)" />
                  <h3>Order Placed Successfully!</h3>
                  <p>Thank you, {checkoutData.name}. Your order will be delivered to your address soon.</p>
                  <p>Payment: <strong>Cash on Delivery</strong></p>
                  <button className="btn btn-primary full-width" style={{marginTop: '30px'}} onClick={() => setIsCartOpen(false)}>Continue Shopping</button>
                </div>
              </div>
            )}
          </div>
        </>
      )}
    </div>
  );
}

export default App;
