<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Lobby</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #e0e0e0;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px 20px;
        }

        .header h1 {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .user-info {
            background: rgba(255, 255, 255, 0.05);
            padding: 8px 16px;
            border-radius: 20px;
            display: inline-block;
            font-size: 0.875rem;
            color: #a0a0a0;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .create-section {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 40px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .create-section h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .create-section h2::before {
            content: '🎮';
            font-size: 1.75rem;
        }

        .form-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .form-group {
                grid-template-columns: 1fr;
            }
        }

        select,
        input[type="text"] {
            width: 100%;
            padding: 14px 18px;
            background: rgba(0, 0, 0, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: #fff;
            font-size: 1rem;
            transition: all 0.3s ease;
            outline: none;
        }

        select:focus,
        input[type="text"]:focus {
            border-color: #667eea;
            background: rgba(0, 0, 0, 0.4);
        }

        select option {
            background: #1a1a2e;
            color: #fff;
        }

        input[type="text"]::placeholder {
            color: #666;
        }

        #create {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 1.125rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        #create:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        #create:active {
            transform: translateY(0);
        }

        .lobby-section h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .lobby-section h2::before {
            content: '🏆';
            font-size: 1.75rem;
        }

        .game-listings {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        @media (max-width: 768px) {
            .game-listings {
                grid-template-columns: 1fr;
            }
        }

        .game-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 24px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .game-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }

        .game-card:hover {
            transform: translateY(-4px);
            border-color: rgba(102, 126, 234, 0.5);
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
        }

        .game-id {
            display: inline-block;
            background: rgba(102, 126, 234, 0.2);
            color: #667eea;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .game-info {
            margin-bottom: 16px;
        }

        .game-type {
            color: #a0a0a0;
            font-size: 0.875rem;
            margin-bottom: 8px;
            text-transform: capitalize;
        }

        .players {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .player {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
        }

        .player::before {
            content: '👤';
            font-size: 1.125rem;
        }

        .player.waiting::before {
            content: '⏳';
        }

        .join-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);
        }

        .join-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 87, 108, 0.5);
        }

        .full-game {
            color: #666;
            font-style: italic;
            text-align: center;
            padding: 12px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 16px;
        }

        .empty-state-text {
            font-size: 1.125rem;
        }
    </style>
</head>

<body>
    <?php
    $user_ip = $_SERVER['REMOTE_ADDR'];
    ?>

    <div class="container">
        <div class="header">
            <h1>Game Lobby</h1>
            <div class="user-info">Connected as: <?= htmlspecialchars($user_ip) ?></div>
        </div>

        <div class="create-section">
            <h2>Create New Game</h2>
            <div class="form-group">
                <select name="game" id="games">
                    <option value="connect-four">Connect Four</option>
                </select>
                <input type="text" id="player-name" placeholder="Enter your name...">
            </div>
            <button id="create">Create Game</button>
        </div>

        <div class="lobby-section">
            <h2>Available Games</h2>
            <div class="game-listings">
                <div class="empty-state">
                    <div class="empty-state-icon">🎲</div>
                    <div class="empty-state-text">No games available. Create one to get started!</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        addEventListener("DOMContentLoaded", (event) => {
            const ip = <?= json_encode($user_ip) ?>;
            const socket = new WebSocket("ws://10.0.0.135:3000/");

            socket.addEventListener('open', event => {
                console.log('WebSocket connection established!');
                socket.send(JSON.stringify('All'));
            });

            socket.addEventListener('message', event => {
                const game = JSON.parse(event.data);
                const listings = document.querySelector('.game-listings');

                if (game.type === 'create') {
                    console.log('game', game.full_data);
                    if (game.full_data.ip === ip) {
                        window.location = 'http://10.0.0.135/games/connect4/main.php?id=' + JSON.parse(game.data).id + '&player=' + JSON.parse(game.data).player1;
                    } else {
                        socket.send(JSON.stringify('All'));
                    }
                } else if (game.type === 'All') {
                    listings.innerHTML = '';

                    if (game.data.length === 0) {
                        listings.innerHTML = `
                            <div class="empty-state">
                                <div class="empty-state-icon">🎲</div>
                                <div class="empty-state-text">No games available. Create one to get started!</div>
                            </div>
                        `;
                        return;
                    }

                    game.data.forEach((row, index) => {
                        let data = row;
                        const gameCard = document.createElement('div');
                        gameCard.className = 'game-card';

                        if (data.player2) {
                            gameCard.innerHTML = `
                                <div class="game-id">Game #${data.id}</div>
                                <div class="game-info">
                                    <div class="game-type">${data.type}</div>
                                    <div class="players">
                                        <div class="player">${data.player1}</div>
                                        <div class="player">${data.player2}</div>
                                    </div>
                                </div>
                                <div class="full-game">Game in progress</div>
                            `;
                        } else {
                            gameCard.innerHTML = `
                                <div class="game-id">Game #${data.id}</div>
                                <div class="game-info">
                                    <div class="game-type">${data.type}</div>
                                    <div class="players">
                                        <div class="player">${data.player1}</div>
                                        <div class="player waiting">Waiting for opponent...</div>
                                    </div>
                                </div>
                                <button class="join-btn" data-game-id="${data.id}">Join Game</button>
                            `;
                        }

                        listings.appendChild(gameCard);
                    });

                    const joinBtns = document.querySelectorAll('.join-btn');
                    joinBtns.forEach((btn) => {
                        btn.addEventListener('click', function(event) {
                            const gameId = this.getAttribute('data-game-id');
                            let name = prompt('Enter your name:');
                            if (name !== null && name !== '') {
                                const payload = {
                                    id: gameId,
                                    player2: name,
                                    type: 'join'
                                };
                                socket.send(JSON.stringify(payload));
                                window.location = 'http://10.0.0.135/games/connect4/main.php?id=' + gameId + '&player=' + name;
                            }
                        });
                    });
                } else if (game.type === 'join') {
                    console.log(game);
                }
            });

            document.getElementById('create').addEventListener('click', () => {
                const player = document.getElementById('player-name').value;
                if (player === null || player === '') {
                    alert('Please enter your name');
                    return;
                }
                const game = document.getElementById('games').value;
                const payload = {
                    player: player,
                    game: game,
                    type: 'create',
                    ip: ip
                };
                console.log(payload);
                socket.send(JSON.stringify(payload));
            });

            socket.addEventListener('close', event => {
                console.log('WebSocket connection closed:', event.code, event.reason);
            });

            socket.addEventListener('error', error => {
                console.error('WebSocket error:', error);
            });
        });
    </script>
</body>

</html>