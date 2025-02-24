import { FaClock } from 'react-icons/fa'; // Dodaj FaClock
import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";

const OneRecipe = () => {
  const { id } = useParams();
  const [recipe, setRecipe] = useState(null);
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
        setRecipe(data);
        setProducts(data.proizvodi || []); // Postavljamo proizvode iz odgovora
        console.log("Setovani proizvodi:", data.proizvodi);
      })
      .catch((error) => console.error("Greška pri učitavanju recepta:", error));
  }, [id]);

  console.log("Products:", products);

  if (!recipe) return <p className="loading">Učitavanje...</p>;

  return (
    <div className="one-recipe-container">
      <img
        className="one-recipe-image"
        src={recipe.slika}
        alt="Recept"
      />
      <div className="one-recipe-content">
        <h2 className="one-recipe-title">{recipe.naziv}</h2>
        <p className="one-recipe-type">{recipe.tip_jela}</p>
        <p className="one-recipe-time">
          <FaClock className="time-icon" /> {recipe.vreme_pripreme} min
        </p>
  
        {/* Sekcija za proizvode */}
        <h3 className="one-recipe-subtitle">Potrebni proizvodi:</h3>
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
  
        <h3 className="one-recipe-subtitle">Opis pripreme:</h3>
        <p className="one-recipe-description">{recipe.opis_pripreme}</p>
      </div>
    </div>
  );
  
};

export default OneRecipe;
