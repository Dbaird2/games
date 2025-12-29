const express = require("express");
const { createClient } = require("redis");
const cors = require("cors");

const app = express();
app.use(cors());
app.use(express.json());

const redis = createClient();
redis.connect();

app.get("/games/init", async (req, res) => {
  // K = king, Q = queen, R = rook, N = knight, B = bishop, P = pawn
  // BP = Black Pawn, WP = White Pawn
  /*
    await redis.hSet('board', {
        0: ["WR", "WN", "WB", "WK", "WQ", "WB", "WN", "WR"],
        1: ["WP", "WP", "WP", "WP", "WP", "WP", "WP", "WP"],
        2: ["", "", "", "", "", "", "", ""],
        3: ["", "", "", "", "", "", "", ""],
        4: ["", "", "", "", "", "", "", ""],
        5: ["", "", "", "", "", "", "", ""],
        6: ["BP", "BP", "BP", "BP", "BP", "BP", "BP", "BP"],
        7: ["BR", "BN", "BB", "BK", "BQ", "BB", "BN", "BR"],
    });
    */
  res.send("Board initialized");
});
app.listen(1234, () => {
  console.log("http://localhost:3000");
});
