import { FaClock } from 'react-icons/fa'; // Dodaj FaClock
import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";

const OneReceipt = () => {
  const { id } = useParams();
  const [receipt, setReceipt] = useState(null);
  const [products, setProducts] = useState([]);
  

  useEffect(() => {
    fetch(`http://localhost:8000/api/recepti/${id}`)
      .then((response) => response.json())
      .then((data) => {
        console.log("Odgovor API:", data);
        if (!data || typeof data !== "object") {
          console.error("API ne vraća validan objekat!", data);
          return;
        }
        console.log("Ključevi u odgovoru API-ja:", Object.keys(data));
        if (!data.proizvodi) {
          console.error("API ne vraća 'proizvodi'!");
          return;
        }
        setReceipt(data);
        setProducts(data.proizvodi || []); // Postavljamo proizvode iz odgovora
        console.log("Setovani proizvodi:", data.proizvodi);
      })
      .catch((error) => console.error("Greška pri učitavanju recepta:", error));
  }, [id]);

  console.log("Products:", products);

  if (!receipt) return <p className="loading">Učitavanje...</p>;

  return (
    <div className="one-receipt-container">
      <img
        className="one-receipt-image"
        src={`https://picsum.photos/600/400?random=${id}`}
        alt="Recept"
      />
      <div className="one-receipt-content">
        <h2 className="one-receipt-title">{receipt.naziv}</h2>
        <p className="one-receipt-type">{receipt.tip_jela}</p>
        <p className="one-receipt-time">
          <FaClock className="time-icon" /> {receipt.vreme_pripreme} min
        </p>
  
        {/* Sekcija za proizvode */}
        <h3 className="one-receipt-subtitle">Potrebni proizvodi:</h3>
        <ul className="products-list">
          {products.map((product) => {
            console.log("Proizvod:", product); // Proverite da li je proizvod ispravan
            console.log("Pivot objekat:", product.pivot);
            return (
              <li key={product.id} className="product-item">
                <div className="product-info">
                  <span className="product-name">{product.naziv}</span>
                  <span className="product-quantity">
                    {product.pivot.kolicina} {product.pivot.merna_jedinica}
                  </span>
                </div>
              </li>
            );
          })}
        </ul>
  
        <h3 className="one-receipt-subtitle">Opis pripreme:</h3>
        <p className="one-receipt-description">{receipt.opis_pripreme}</p>
      </div>
    </div>
  );
  
};

export default OneReceipt;
