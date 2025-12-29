const WebSocket = require("ws");

// Create a WebSocket server on port 8080
const wss = new WebSocket.Server({ host: "0.0.0.0", port: 3001 });

// Connection event handler
wss.on("connection", (ws) => {
  console.log("New client connected");

  // Message event handler
  ws.on("message", async (message) => {
    const update_url = "http://10.0.0.135/games/update-game.php";
    const delete_game = "http://10.0.0.135/games/delete-game.php";

    console.log(`Received: ${message}`);
    // FETCH TO PHP TO ADD TO SERVER
    async function gameFetch(url, message) {
      const res = await fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: message,
      });

      if (!res.ok) {
        throw new Error(`Error ${res.status}`);
      }
      const data = await res.json();

      return data;
    }
    const json_msg = JSON.parse(message);
    console.log(json_msg);

    if (json_msg.type === "update-board") {
      console.log("sending update board", json_msg);
      wss.clients.forEach((client) => {
        client.send(JSON.stringify(json_msg));
      });
    } else if (json_msg.type === "game-over") {
      // DELETE GAME FROM DB USING ID (FETCH)
      // await gameFetch(delete_game, json_msg);
      wss.clients.forEach((client) => {
        client.send(JSON.stringify(json_msg));
      });
    } else if (json_msg.type === "update") {
      data = await gameFetch(update_url, message);
      payload = {
        data: data,
        type: json_msg.type,
      };
      console.log(data);
      ws.send(JSON.stringify(payload));
    } else if (json_msg.type === "chess-update") {
      wss.clients.forEach((client) => {
        client.send(JSON.stringify(json_msg));
      });
    } else if (json_msg.type === "chess-join") {
      wss.clients.forEach((client) => {
        client.send(JSON.stringify(json_msg));
      });
    }
  });

  // Close event handler
  ws.on("close", () => {
    console.log("Client disconnected");
  });
});
