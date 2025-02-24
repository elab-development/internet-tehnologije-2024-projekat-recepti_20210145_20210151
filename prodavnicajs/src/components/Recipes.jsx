import { useEffect, useState } from "react";
import { Link } from "react-router-dom";

const Recipes = () => {
  const [recipes, setRecipes] = useState([]);
  const [pagination, setPagination] = useState({}); // Inicijalizujemo kao objekat

  useEffect(() => {
    fetch("http://localhost:8000/api/recepti/pretraga?per_page=9")
      .then((response) => response.json())
      .then((data) => {
        console.log("Dobijeni podaci:", data);

        if (data.recepti && Array.isArray(data.recepti)) {
          setRecipes(data.recepti);
          setPagination(data.pagination || {}); // Dodajemo proveru
        } else {
          console.error("Neočekivan format podataka:", data);
          setRecipes([]);
        }
      })
      .catch((error) => {
        console.error("Greška pri učitavanju recepata:", error);
        setRecipes([]);
      });
  }, []); // Ovdje bi trebalo da se postavi samo prilikom prvog učitavanja

  // Funkcija za promenu stranice
  const handlePageChange = (page) => {
    if (page < 1 || page > pagination?.last_page) return;

    fetch(`http://localhost:8000/api/recepti/pretraga?per_page=9&page=${page}`)
      .then((response) => response.json())
      .then((data) => {
        if (data.recepti && Array.isArray(data.recepti)) {
          setRecipes(data.recepti);
          setPagination(data.pagination || {}); // Dodajemo proveru
        }
      })
      .catch((error) => {
        console.error("Greška pri učitavanju stranice:", error);
      });
  };

  return (
    <div className="recipes-container">
      <h2 className="title">Svi Recepti</h2>
      {recipes.length === 0 ? (
        <p className="no-recipes">Nema dostupnih recepata.</p>
      ) : (
        <div className="recipes-grid">
          {recipes.map((recipe, index) => (
            <div key={recipe.id} className="recipe-card">
              <div className="recipe-image">
                <img
                  src={recipe.slika}
                  alt="Recept"
                />
              </div>
              <div className="recipe-info">
                <h3 className="recipe-title">{recipe.naziv}</h3>
                <p className="recipe-type">{recipe.tip_jela}</p>
                <p className="recipe-time">Vreme pripreme: {recipe.vreme_pripreme} min</p>
                <Link to={`/recepti/${recipe.id}`} className="view-button">
                  Prikaži više
                </Link>
              </div>
            </div>
          ))}
        </div>
      )}

      {/* Dodaj paginaciju ako je 'pagination' prisutan */}
      {pagination && pagination.total > 0 && (
        <div className="pagination">
          {/* Dugme za prethodnu stranicu samo ako nismo na prvoj stranici */}
          {pagination.current_page > 1 && (
            <button
              onClick={() => handlePageChange(pagination.current_page - 1)}
            >
              Prethodna
            </button>
          )}

          <span>
            {pagination.current_page} / {pagination.last_page}
          </span>

          {/* Dugme za sledeću stranicu samo ako nismo na poslednjoj stranici */}
          {pagination.current_page < pagination.last_page && (
            <button
              onClick={() => handlePageChange(pagination.current_page + 1)}
            >
              Sledeća
            </button>
          )}
        </div>
      )}
    </div>
  );
};

export default Recipes;
