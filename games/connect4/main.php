<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect Four - Game Arena</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #fff;
        }

        .game-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            max-width: 600px;
            width: 100%;
        }

        .game-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .game-title {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        .turn-indicator {
            font-size: 1.25rem;
            font-weight: 600;
            padding: 12px 24px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            display: inline-block;
            margin-top: 15px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .turn-indicator.player1 {
            background: rgba(239, 68, 68, 0.2);
            border-color: #ef4444;
            color: #fca5a5;
        }

        .turn-indicator.player2 {
            background: rgba(59, 130, 246, 0.2);
            border-color: #3b82f6;
            color: #93c5fd;
        }

        .board-wrapper {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 8px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
        }

        .board {
            background: #2d3748;
            border-radius: 12px;
            padding: 12px;
            display: inline-block;
        }

        .board-row {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .board-row:last-child {
            margin-bottom: 0;
        }

        .cell {
            width: 61px;
            height: 60px;
            background: #1a202c;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .cell.clickable:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.5);
        }

        .cell.column-button {
            background: rgba(102, 126, 234, 0.3);
            border: 2px solid rgba(102, 126, 234, 0.5);
            font-size: 1.5rem;
            font-weight: bold;
            color: #a5b4fc;
        }

        .cell.column-button:hover {
            background: rgba(102, 126, 234, 0.5);
            border-color: #667eea;
            color: #fff;
        }

        .cell.player1 {
            background: radial-gradient(circle, #3b82f6 0%, #2563eb 100%);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.6), inset 0 2px 4px rgba(255, 255, 255, 0.3);
            animation: dropPiece 0.4s ease;
        }

        .cell.player2 {
            background: radial-gradient(circle, #ef4444 0%, #dc2626 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.6), inset 0 2px 4px rgba(255, 255, 255, 0.3);
            animation: dropPiece 0.4s ease;
        }

        @keyframes dropPiece {
            0% {
                transform: translateY(-300px) scale(0.5);
                opacity: 0;
            }

            60% {
                transform: translateY(10px) scale(1.05);
            }

            100% {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }

        .player-info {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
            gap: 20px;
        }

        .player-card {
            flex: 1;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            border: 2px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .player-card.active {
            transform: scale(1.05);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .player-card.player1-card {
            border-color: rgba(239, 68, 68, 0.3);
        }

        .player-card.player1-card.active {
            border-color: #ef4444;
            box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4);
        }

        .player-card.player2-card {
            border-color: rgba(59, 130, 246, 0.3);
        }

        .player-card.player2-card.active {
            border-color: #3b82f6;
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.4);
        }

        .player-label {
            font-size: 0.875rem;
            color: #9ca3af;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .player-color {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin: 0 auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .player1-color {
            background: radial-gradient(circle, #ef4444 0%, #dc2626 100%);
        }

        .player2-color {
            background: radial-gradient(circle, #3b82f6 0%, #2563eb 100%);
        }

        @media (max-width: 640px) {
            .game-container {
                padding: 20px;
            }

            .cell {
                width: 1.93rem;
                height: 1.93rem;
            }

            .game-title {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <div class="game-container">
        <div class="game-header">
            <h1 class="game-title">Connect Four</h1>
            <p class="turn-indicator player1">Player 1 Turn</p>
        </div>

        <div class="board-wrapper">
            <div class="board">
                <?php
                // Define the game board size
                $board_size = 7;
                $player = $_GET['player'];
                $id = $_GET['id'];
                // Initialize the game board with empty cells
                $board = array_fill(0, $board_size, array_fill(0, $board_size, ''));

                // Function to display the game board
                function displayBoard($board)
                {
                    foreach ($board as $index => $row) {
                        echo '<div class="board-row">';
                        foreach ($row as $row_index => $cell) {
                            if ($index === 0) {
                                echo '<div id="' . $index . $row_index . '" data-row="' . $row_index . '" data-column="' . $index . '" class="cell clickable column-button">↓</div>';
                            } else {
                                echo '<div id="' . $index . $row_index . '" data-row="' . $row_index . '" data-column="' . $index . '" class="cell"></div>';
                            }
                        }
                        echo '</div>';
                    }
                }

                // Display the initial game board
                displayBoard($board);
                ?>
            </div>
        </div>

        <div class="player-info">
            <div class="player-card player1-card active">
                <div class="player-label">Player 1</div>
                <div class="player-color player1-color"></div>
            </div>
            <div class="player-card player2-card">
                <div class="player-label">Player 2</div>
                <div class="player-color player2-color"></div>
            </div>
        </div>
    </div>

    <script>
        function updateBoard(board, turn) {
            const turnIndicator = document.querySelector('.turn-indicator');
            const player1Card = document.querySelector('.player1-card');
            const player2Card = document.querySelector('.player2-card');

            if (turn === 2) {
                turnIndicator.textContent = 'Player 2 Turn';
                turnIndicator.classList.remove('player1');
                turnIndicator.classList.add('player2');
                player1Card.classList.remove('active');
                player2Card.classList.add('active');
            } else {
                turnIndicator.textContent = 'Player 1 Turn';
                turnIndicator.classList.remove('player2');
                turnIndicator.classList.add('player1');
                player2Card.classList.remove('active');
                player1Card.classList.add('active');
            }
            let column_size = board[0].length;
            let row_size = board.length;
            for (let i = 1; i <= 6; i++) {
                for (let j = 0; j <= 6; j++) {
                    const cell = document.getElementById(String(i) + String(j));
                    cell.classList.remove('player1', 'player2');
                    if (board[i][j] === 1) {
                        cell.classList.add('player1');
                    } else if (board[i][j] === 2) {
                        cell.classList.add('player2');
                    }
                }
            }
        }

        function validMove(indexes, board) {
            indexes = indexes.split("");
            for (let i = 6; i > 0; i--) {
                if (i === 0) {
                    break;
                }
                //console.log(board[0][i], i);
                if (board[i][indexes[1]] === "") {
                    return true;
                }
            }
            alert('invalid move');
            return false;
        }

        function gameOver(socket, payload, player, who) {
            console.log('game is over');


            alert(player + ' won');
            if (who === 'sending') {
                socket.send(JSON.stringify(payload));
            }
            return 1;
        }

        function checkBoard(socket, board, turn, id, who) {
            const row = board.length;
            const column = board[0].length;
            let player;
            if (turn === 1) {
                player = 'Player 2';
            } else {
                player = 'Player 1';
            }
            let payload = {
                game: 'connect-four',
                winner: player,
                type: 'game-over',
                board: board,
                id: id
            };
            //console.log(payload);
            console.log('Checking board');
            let game_over = 0;
            for (let i = 0; i < board.length; i++) {
                for (let j = 0; j < board[0].length; j++) {
                    if (
                        column - j >= 4 &&
                        board[i][j] === 1 &&
                        board[i][j + 1] === 1 &&
                        board[i][j + 2] === 1 &&
                        board[i][j + 3] === 1
                    ) {
                        game_over = gameOver(socket, payload, player, who)
                        return game_over;
                    } else if (
                        row - i >= 4 &&
                        board[i][j] === 1 &&
                        board[i + 1][j] === 1 &&
                        board[i + 2][j] === 1 &&
                        board[i + 3][j] === 1
                    ) {
                        game_over = gameOver(socket, payload, player, who)
                        return game_over;
                    } else if (
                        row - i >= 4 &&
                        column - j >= 4 &&
                        board[i][j] === 1 &&
                        board[i + 1][j + 1] === 1 &&
                        board[i + 2][j + 2] === 1 &&
                        board[i + 3][j + 3] === 1
                    ) {
                        game_over = gameOver(socket, payload, player, who)
                        return game_over;
                    } else if (
                        i - 3 >= 0 &&
                        column - j >= 4 &&
                        board[i][j] === 1 &&
                        board[i - 1][j + 1] === 1 &&
                        board[i - 2][j + 2] === 1 &&
                        board[i - 3][j + 3] === 1
                    ) {
                        game_over = gameOver(socket, payload, player, who)
                        return game_over;
                    } else if (
                        i - 3 >= 0 &&
                        j - 1 >= 0 &&
                        board[i][j] === 1 &&
                        board[i - 1][j - 1] === 1 &&
                        board[i - 2][j - 2] === 1 &&
                        board[i - 3][j - 3] === 1
                    ) {
                        game_over = gameOver(socket, payload, player, who)
                        return game_over;
                    } else if (
                        row - i >= 4 &&
                        j - 3 >= 0 &&
                        board[i][j] === 1 &&
                        board[i + 1][j - 1] === 1 &&
                        board[i + 2][j - 2] === 1 &&
                        board[i + 3][j - 3] === 1
                    ) {
                        game_over = gameOver(socket, payload, player, who)
                        return game_over;
                    } else if (
                        column - j >= 4 &&
                        board[i][j] === 2 &&
                        board[i][j + 1] === 2 &&
                        board[i][j + 2] === 2 &&
                        board[i][j + 3] === 2
                    ) {
                        game_over = gameOver(socket, payload, player, who)
                        return game_over;
                    } else if (
                        row - i >= 3 &&
                        board[i][j] === 2 &&
                        board[i + 1][j] === 2 &&
                        board[i + 2][j] === 2 &&
                        board[i + 3][j] === 2
                    ) {
                        game_over = gameOver(socket, payload, player, who)
                        return game_over;
                    } else if (
                        row - i >= 4 &&
                        column - j >= 4 &&
                        board[i][j] === 2 &&
                        board[i + 1][j + 1] === 2 &&
                        board[i + 2][j + 2] === 2 &&
                        board[i + 3][j + 3] === 2
                    ) {
                        game_over = gameOver(socket, payload, player, who)
                        return game_over;
                    } else if (
                        i - 3 >= 0 &&
                        column - j >= 4 &&
                        board[i][j] === 2 &&
                        board[i - 1][j + 1] === 2 &&
                        board[i - 2][j + 2] === 2 &&
                        board[i - 3][j + 3] === 2
                    ) {
                        game_over = gameOver(socket, payload, player, who)
                        return game_over;
                    } else if (
                        i - 3 >= 0 &&
                        j - 3 >= 0 &&
                        board[i][j] === 2 &&
                        board[i - 1][j - 1] === 2 &&
                        board[i - 2][j - 2] === 2 &&
                        board[i - 3][j - 3] === 2
                    ) {
                        game_over = gameOver(socket, payload, player, who)
                        return game_over;
                    } else if (
                        row - i >= 4 &&
                        j - 3 >= 0 &&
                        board[i][j] === 2 &&
                        board[i + 1][j - 1] === 2 &&
                        board[i + 2][j - 2] === 2 &&
                        board[i + 3][j - 3] === 2
                    ) {
                        game_over = gameOver(socket, payload, player, who)
                        return game_over;
                    }
                }
                //console.log(payload)

            }
            //console.log(game_over);

        }
        document.addEventListener('DOMContentLoaded', () => {
            const id = <?= $_GET['id']; ?>;
            const player = String(<?= json_encode($_GET['player']); ?>);
            const socket = new WebSocket("ws://10.0.0.135:3001/");

            socket.addEventListener('open', event => {
                console.log('WebSocket connection established!');
                socket.send(JSON.stringify('Starting'));
            });

            let board = <?= json_encode($board) ?>;
            let column_size = board[0].length;
            let row_size = board.length;
            let row = ['00', '01', '02', '03', '04', '05', '06'];

            for (let q = 0; q < 7; q++) {
                document.getElementById(row[q]).addEventListener('click', () => {
                    const payload = {
                        game: 'connect-four',
                        id: id,
                        player: player,
                        type: 'update',
                        placement: row[q]
                    };
                    let valid = validMove(row[q], board);
                    if (valid) {
                        socket.send(JSON.stringify(payload));
                    }
                });
            }

            socket.addEventListener('message', msg => {

                console.log('main.php', JSON.parse(msg.data), 'placement ' + JSON.parse(msg.data).placement, 'type ' + JSON.parse(msg.data).type);
                let placement;
                let column;

                if (JSON.parse(msg.data).type === 'update') {
                    console.log('update');
                    placement = JSON.parse(msg.data).data.placement.split("");
                    const row = parseInt(placement[0]);
                    column = parseInt(placement[1]);
                    const turn = JSON.parse(msg.data).data.turn;
                    let found = 0;
                    let f = 0;

                    //console.log(board);
                    for (let i = 6; i > row; i--) {
                        if (i === 0) {
                            break;
                        }
                        //console.log(board[0][i], i);
                        if (board[i][column] === "") {
                            board[i][column] = turn;
                            f = i;
                            found = 1;
                            break;
                        }
                    }

                    if (found === 0) {
                        alert('Invalid move - column is full!');
                    } else {


                        //updateBoard(board);
                        const payload = {
                            type: 'update-board',
                            id: id,
                            board: board,
                            placement: placement[0] + placement[1],
                            turn: turn
                        };
                        socket.send(JSON.stringify(payload));
                    }
                } else if (JSON.parse(msg.data).type === 'update-board') {
                    if (JSON.parse(msg.data).id !== id) {
                        return;
                    }
                    console.log('update-board', msg.data)
                    placement = JSON.parse(msg.data).placement.split("");
                    column = parseInt(placement[1]);
                    board = JSON.parse(msg.data).board;
                    updateBoard(board, JSON.parse(msg.data).turn);
                    let game_over = checkBoard(socket, board, JSON.parse(msg.data).turn, JSON.parse(msg.data).id, 'sending');

                } else if (JSON.parse(msg.data).type === 'game-over' && id === JSON.parse(msg.data).id) {
                    updateBoard(board, JSON.parse(msg.data).turn);
                    if (emptyBoardCheck(board) && confirm('Game is over. Restart match?')) {
                        board = <?= json_encode($board) ?>;

                    } else {
                        const id = <?= $_GET['id']; ?>;
                        const delete_game = "http://10.0.0.135/games/delete-game.php";

                        gameFetch(delete_game, id);
                        console.log('game over routing')
                        setTimeout(() => {
                            window.location = 'http://10.0.0.135:/games/game-select.php';
                        }, 1000);
                    }


                } else {
                    console.log('returning');
                    return;
                }
            });
        });
        async function gameFetch(url, message) {
            console.log('gameFetch Called');
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

        function emptyBoardCheck(board) {
            for (let i = 0; i <= 6; i++) {
                if (board[i][6] !== '') {
                    console.log('not empty');
                    return false;
                }
            }
            console.log('empty');
            return true;
        }
    </script>
</body>

</html>