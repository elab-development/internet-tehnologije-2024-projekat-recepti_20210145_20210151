import { useEffect, useState } from "react";
import { useLocation } from "react-router-dom"; // Za uzimanje parametara iz URL-a
import { Link } from "react-router-dom";

const SearchReceipts = () => {
  const location = useLocation(); // Koristi useLocation za pristup URL-u
  const [receipts, setReceipts] = useState([]);
  const [pagination, setPagination] = useState({}); // Dodajemo stanje za paginaciju
  const [loading, setLoading] = useState(true);
  const [currentPage, setCurrentPage] = useState(1); // Trenutna stranica

  useEffect(() => {
    const queryParams = new URLSearchParams(location.search);
    const tipJela = queryParams.get("tip_jela");

    console.log("Tip jela:", tipJela);

    // URL za pretragu sa parametrima, uključujući trenutnu stranicu
    const url = `http://localhost:8000/api/recepti/pretraga?tip_jela=${tipJela}&page=${currentPage}`;
    console.log("Poslati URL:", url);

    setLoading(true); // Početak učitavanja

    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        console.log("Dobijeni podaci:", data);

        // Ako su podaci u formatu koji se očekuje
        if (Array.isArray(data.recepti)) {
          setReceipts(data.recepti);
          setPagination(data.pagination || {}); // Spremi paginaciju, ako postoji
        } else {
          console.error("Neočekivan format podataka:", data);
          setReceipts([]);
        }
      })
      .catch((error) => {
        console.error("Greška pri učitavanju recepata:", error);
        setReceipts([]);
      })
      .finally(() => {
        setLoading(false); // Završeno učitavanje
      });
  }, [location, currentPage]); // useEffect zavisi od location i currentPage

  // Funkcija za promenu stranice
  const handlePageChange = (page) => {
    if (page < 1 || page > pagination.last_page) return; // Proveravamo da li je stranica unutar granica
    setCurrentPage(page);
  };

  return (
    <div className="receipts-container">
      <h2 className="title">Recepti - {new URLSearchParams(location.search).get("tip_jela")}</h2>
      {loading ? (
        <p>Učitavanje...</p>
      ) : receipts.length === 0 ? (
        <p className="no-recipes">Nema dostupnih recepata za ovaj tip jela.</p>
      ) : (
        <div className="recipes-grid">
          {receipts.map((receipt, index) => (
            <div key={receipt.id} className="recipe-card">
              <div className="recipe-image">
                <img
                  src={`https://picsum.photos/300/200?random=${index}`}
                  alt="Recept"
                />
              </div>
              <div className="recipe-info">
                <h3 className="recipe-title">{receipt.naziv}</h3>
                <p className="recipe-type">{receipt.tip_jela}</p>
                <p className="recipe-time">Vreme pripreme: {receipt.vreme_pripreme} min</p>
                <Link to={`/recepti/${receipt.id}`} className="view-button">
                  Prikaži više
                </Link>
              </div>
            </div>
          ))}
        </div>
      )}

      {/* Paginacija */}
      {pagination.total > 0 && (
        <div className="pagination">
          {/* Dugme za prethodnu stranicu */}
          {pagination.current_page > 1 && (
            <button onClick={() => handlePageChange(pagination.current_page - 1)}>
              Prethodna
            </button>
          )}

          {/* Prikaz trenutne stranice */}
          <span>{pagination.current_page} / {pagination.last_page}</span>

          {/* Dugme za sledeću stranicu */}
          {pagination.current_page < pagination.last_page && (
            <button onClick={() => handlePageChange(pagination.current_page + 1)}>
              Sledeća
            </button>
          )}
        </div>
      )}
    </div>
  );
};

export default SearchReceipts;
