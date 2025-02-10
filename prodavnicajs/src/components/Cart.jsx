import React, { useContext } from 'react';
import { CartContext } from '../context/CartContext';

const Cart = () => {
    const { cart, updateQuantity } = useContext(CartContext);
    console.log("Stanje korpe u Cart komponenti:", cart);
    
    //const total = cart.reduce((sum, item) => sum + item.cena * item.kolicina_proizvoda, 0);
    const total = cart.reduce((sum, item) => sum + item.cena * item.pivot.kolicina_proizvoda, 0);

    return (
        <div className="cart">
            <h2>Vaša korpa</h2>
            {cart.length === 0 ? (
                <p>Korpa je prazna.</p>
            ) : (
                <div>
                    {cart.map(item => (
                        
                        <div key={item.proizvod_id} className="cart-item">
                            <img src={item.slika || "https://picsum.photos/50"} alt={item.naziv} />
                            <p>{item.naziv}</p>
                            <p>{item.cena} RSD</p>
                            <div>
                                <button onClick={() => updateQuantity(item.proizvod_id, "decrease")}>-</button>
                                <p>{item.pivot.kolicina_proizvoda}</p>
                                <button onClick={() => updateQuantity(item.proizvod_id, "increase")}>+</button>
                            </div>
                        </div>
                    ))}
                    <h3>Ukupno: {total} RSD</h3>
                </div>
            )}
        </div>
    );
};

export default Cart;

