import React, { useContext } from 'react';
import { useNavigate } from 'react-router-dom'; // Za navigaciju
import { CartContext } from '../context/CartContext';

const Cart = () => {
    const { cart, updateQuantity } = useContext(CartContext);
    const navigate = useNavigate();

    console.log("Stanje korpe u Cart komponenti:", cart);
    
    const total = cart.reduce((sum, item) => sum + item.cena * item.pivot.kolicina_proizvoda, 0);


    const handleCheckout = () => {
        // Navigate to the purchase page
        navigate('/kupovina', { state: { total: total } });  // <-- Navigate to the purchase page
    };

    return (
        <div className="cart">
            <h2>Vaša korpa</h2>
            {cart.length === 0 ? (
                <p>Korpa je prazna.</p>
            ) : (
                <div>
                    {cart.map(item => (
                        
                        <div key={item.proizvod_id} className="cart-item">
                            <img src={item.slika} alt={item.naziv} />
                            <p>{item.naziv}</p>
                            <p>{item.cena} RSD</p>
                            <div>
                                <button onClick={() => updateQuantity(item.pivot.proizvod_id, "decrease")}>-</button>
                                <p>{item.pivot.kolicina_proizvoda}</p>
                                <button onClick={() => updateQuantity(item.pivot.proizvod_id, "increase")}>+</button>
                            </div>
                        </div>
                    ))}
                    <div className="cart-total">
                        <h3>Ukupno: {total} RSD</h3>
                        <button className="buy-button" onClick={handleCheckout}> Kupi </button>
                    </div>
                </div>
            )}
        </div>
    );
};

export default Cart;