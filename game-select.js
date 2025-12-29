const WebSocket = require("ws");

// Create a WebSocket server on port 8080
const wss = new WebSocket.Server({ host: "0.0.0.0", port: 3000 });

// Connection event handler
wss.on("connection", (ws) => {
  console.log("New client connected");

  // Message event handler
  ws.on("message", async (message) => {
    const add_url = "http://10.0.0.135/games/add-game.php";
    const update_url = "http://10.0.0.135/games/connect4/update-game.php";
    const all_url = "http://10.0.0.135/games/all-game.php";
    const join_url = "http://10.0.0.135/games/join-game.php";
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
    let data;
    let payload;
    if (json_msg.type === "create") {
      data = await gameFetch(add_url, message);
      payload = {
        data: data.data,
        type: json_msg.type,
        full_data: data,
      };
      wss.clients.forEach((client) => {
        if (client.readyState === WebSocket.OPEN) {
          client.send(JSON.stringify(payload));
        }
      });
    } else if (json_msg.type === "join") {
      console.log("join");

      data = await gameFetch(join_url, message);
      console.log("join data", data);
      payload = {
        data: data.data,
        type: json_msg.type ?? "All",
      };
      ws.send(JSON.stringify(payload));
    } else if (json_msg.type === "update-board") {
      console.log("sending update board", json_msg);
      wss.clients.forEach((client) => {
        client.send(JSON.stringify(json_msg));
      });
    } else if (json_msg.type === "game-over") {
      //await gameFetch(delete_game, json_msg);
      // DELETE GAME FROM DB USING ID (FETCH)
      wss.clients.forEach((client) => {
        client.send(JSON.stringify(json_msg));
      });
    } else {
      data = await gameFetch(all_url, message);
      payload = {
        data: data.data,
        type: json_msg.type ?? "All",
      };
      ws.send(JSON.stringify(payload));
    }

    // SEND ID, PLAYER NAME, GAME NAME BACK TO PAGE TO UPDATE
    if (json_msg.type === "create") {
    } else {
    }
  });

  // Close event handler
  ws.on("close", () => {
    console.log("Client disconnected");
  });
});
