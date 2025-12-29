const { connectRedis } = require("./redis");

async function startApp() {
  await connectRedis();
  console.log("App started");
}

startApp();
