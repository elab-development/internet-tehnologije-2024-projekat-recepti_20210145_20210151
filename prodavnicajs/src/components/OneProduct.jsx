
import React, { useContext } from 'react';
import { CartContext} from '../context/CartContext';

function OneProduct({ product }) {

  //const { addToCart } = useCartContext();
  const { fetchCart } = useContext(CartContext); 
  const handleAddToCart = async (product) => {
    try {
      console.log("Proizvod koji se dodaje u korpu", product);
      // Simulacija količine, ovde bi trebalo da se uzme količina iz input polja ili sl.
      const quantity = 1;

      const token = localStorage.getItem('token');
      if (!token) {
        console.error("Nema tokena, korisnik nije ulogovan!");
        return;
      }


      // Slanje POST zahteva na API
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
        //addToCart(product); // Dodaj proizvod u lokalnu korpu ako je uspešno dodan (addToCart je fja iz CartContext.jsx)
        await fetchCart();
        alert("Uspesno! " + data.message); // Obavesti korisnika
      } else {
        alert("Greska" + data.message); // Obavesti korisnika u slučaju greške
      }
    } catch (error) {
      console.error('Greška pri dodavanju proizvoda:', error);
      alert('Došlo je do greške pri dodavanju proizvoda u korpu.');
    }
  };

  /*const handleRemoveFromCart = async (productId) => {
    try {
        const token = localStorage.getItem('token');
        if (!token) {
            console.error("Nema tokena, korisnik nije ulogovan!");
            return;
        }

        // Poziv API-ja za uklanjanje proizvoda iz korpe
        const response = await fetch('http://localhost:8000/api/korpa/remove-product', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({
                proizvod_id: productId,
            }),
        });

        const data = await response.json();

        if (response.ok) {
            // Osvežavanje podataka u CartContext-u
            await fetchCart();
            alert("Proizvod uspešno uklonjen iz korpe!");
        } else {
            alert("Greška: " + data.message);
        }
    } catch (error) {
        console.error('Greška pri uklanjanju proizvoda:', error);
        alert('Došlo je do greške pri uklanjanju proizvoda iz korpe.');
    }
};*/


/*const handleUpdateQuantity = async (productId, newQuantity) => {
    try {
        const token = localStorage.getItem('token');
        if (!token) {
            console.error("Nema tokena, korisnik nije ulogovan!");
            return;
        }

        // Poziv API-ja za ažuriranje količine proizvoda
        const response = await fetch('http://localhost:8000/api/korpa/update-product', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({
                proizvod_id: productId,
                kolicina: newQuantity,
            }),
        });

        const data = await response.json();

        if (response.ok) {
            // Osvežavanje podataka u CartContext-u
            await fetchCart();
            alert("Količina proizvoda uspešno ažurirana!");
        } else {
            alert("Greška: " + data.message);
        }
    } catch (error) {
        console.error('Greška pri ažuriranju količine proizvoda:', error);
        alert('Došlo je do greške pri ažuriranju količine proizvoda.');
    }
};*/ 
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
