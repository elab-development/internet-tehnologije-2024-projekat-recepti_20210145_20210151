
import React, { useContext } from 'react';

import { CartContext} from '../context/CartContext';

function OneProduct({ product }) {

  const { cart, updateQuantity, fetchCart } = useContext(CartContext); 

  const handleAddToCart = async (product) => {
    try {
        console.log("Proizvod koji se dodaje u korpu", product);

        
        const token = localStorage.getItem('token');
        if (!token) {
            console.error("Nema tokena, korisnik nije ulogovan!");
            return;
        }

        // Provera da li proizvod već postoji u korpi
        const existingProduct = cart.find(item => item.proizvod_id === product.id);

        const quantity = 1;  // Početna količina koja se dodaje ili menja
        if (existingProduct) {
          // Ako proizvod već postoji u korpi, uzmi trenutnu količinu
          quantity = existingProduct.kolicina + 1; // Povećaj količinu za 1
        }
        
        if (existingProduct) {
          // Ako proizvod postoji, pozivaš updateQuantity
          await updateQuantity(product.id, "increase");  // Čekanje na API poziv da se izvrši
          await fetchCart();
          alert("Količina proizvoda je povećana.");
      } else {
          // Ako proizvod ne postoji, dodaješ ga u korpu
          const response = await fetch('http://localhost:8000/api/korpa/add-product', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'Authorization': `Bearer ${token}`,
              },
              body: JSON.stringify({
                  proizvod_id: product.id,
                  kolicina: quantity,
              }),
          });

          const data = await response.json();

          if (response.ok) {
              // Osveži korpu nakon što je proizvod dodat
              await fetchCart();  // Očekuj da se API poziv završi pre nego što osvežiš UI
              alert("Uspesno dodavanje proizvoda u korpu: " + data.message);
          } else {
              alert("Greška: " + data.message);
          }
      }
  } catch (error) {
      console.error('Greška pri dodavanju proizvoda:', error);
      alert('Došlo je do greške pri dodavanju proizvoda u korpu.');
  }
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
