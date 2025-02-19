import { useEffect, useState } from "react";
import { Link } from "react-router-dom";

const Receipts = () => {
  const [receipts, setReceipts] = useState([]);

  useEffect(() => {
    fetch("http://localhost:8000/api/recepti/pretraga")
      .then((response) => response.json())
      .then((data) => {
        console.log("Dobijeni podaci:", data);

        if (Array.isArray(data)) {
          setReceipts(data);
        } else if (Array.isArray(data.recepti)) {
          setReceipts(data.recepti);
        } else {
          console.error("Neočekivan format podataka:", data);
          setReceipts([]);
        }
      })
      .catch((error) => {
        console.error("Greška pri učitavanju recepata:", error);
        setReceipts([]);
      });
  }, []);

  useEffect(() => {
    console.log("Receipts state:", receipts);
  }, [receipts]);

  return (
    <div className="receipts-container">
      <h2 className="title">Svi Recepti</h2>
      {receipts.length === 0 ? (
        <p className="no-recipes">Nema dostupnih recepata.</p>
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
    </div>
  );
};

export default Receipts;
