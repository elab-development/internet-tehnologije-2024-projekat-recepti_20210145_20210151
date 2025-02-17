
import React, { useContext } from 'react';

import { CartContext} from '../context/CartContext';

function OneProduct({ product }) {

  const { addToCart } = useContext(CartContext); 

    const handleAddToCart = async () => {
        console.log("Proizvod koji se dodaje u korpu:", product);
        await addToCart(product);  // Pozovi funkciju addToCart iz CartContext-a
    };


    return (
        <div className="card">
            <img className="card-img" src={product.slika || "https:/picsum.photos/200"} alt={product.naziv} />
            <div className="card-body">
                <h3 className="card-title">{product.naziv}</h3>
                <p className="card-text">{product.kategorija}</p>
                <p className="card-text">{product.tip}</p>
                {/*<p className="card-text">{product.dostupna_kolicina}</p>*/}
                <p className="card-price">{product.cena} RSD</p>
            </div>
            <button className="btn" onClick={() => handleAddToCart(product)}>Dodaj u korpu</button>
        </div>
    );
}

export default OneProduct; 
