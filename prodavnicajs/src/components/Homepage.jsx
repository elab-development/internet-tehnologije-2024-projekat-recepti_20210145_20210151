/*import React from "react";
import { useNavigate } from "react-router-dom";

const Homepage = () => {
  const navigate = useNavigate();
  return (
    <div className="homepage-container">
      <div className="background-image">
        <div className="overlay">
          <h1 className="inspiration-text">
            "Kuvaj pametno, živi zdravo. Tvoj frižider, tvoji recepti."
          </h1>
          <button className="start-button" onClick={() => navigate("/recipe-finder")}>
          Započni kuvanje!
          </button>
        </div>
      </div>
    </div>
  );
};

export default Homepage;*/

import React from "react";
import { useNavigate } from "react-router-dom";

const Homepage = () => {
  const navigate = useNavigate();
  return (
    <div className="homepage-container">
      <div className="background-image"></div>  {/* Pozadina odvojena od overlay-a */}
      <div className="overlay">
        <h1 className="inspiration-text">
          "Kuvaj pametno, živi zdravo. Tvoj frižider, tvoji recepti."
        </h1>
        <button className="start-button" onClick={() => navigate("/recipe-finder")}>
          Započni kuvanje!
        </button>
      </div>
    </div>
  );
};

export default Homepage;
