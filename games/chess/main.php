<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../dist/output.css" rel="stylesheet">


    <title>Chess ID:<?= $_GET['id'] ?></title>
</head>
<style>
    body {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);

    }

    chess-cell {
        width: 100%;
        /* Adjust size as needed */
        height: 110%;
        /* Adjust size as needed */
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        /* adjust piece size */
        /* Adjust piece size */
    }

    .board {
        width: 60%;
        height: 100%;
        font-size: 3.3rem;
    }

    @media (max-width: 768px) {

        /* CSS rules for screens up to 768px wide (common breakpoint for phones/small tablets) */
        .board {
            font-size: 3.5rem;
            width: 100%;
        }
    }
</style>
<?php
if (isset($_GET['player1'])) {
    $player_num = 'white';
} else {
    $player_num = 'black';
}
?>


<body class="bg-gray-200 flex items-center justify-center min-h-screen">

    <div class="board 
            grid grid-cols-8 grid-rows-8 
            w-full max-w-md aspect-square 
            shadow-xl rounded-lg 
            overflow-hidden 
            border border-gray-500">
        <?php
        for ($i = 0; $i <= 7; $i++) {
            for ($j = 0; $j <= 7; $j++) {
                echo '<div id="' . $i . $j . '" class="chess-cell bg-white w-full h-full flex items-center justify-center border border-gray-400"></div>';
            }
        }
        ?>
    </div>

    <!--<button onclick="init()">Start Board</button>-->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let left = {
                left_castle: true,
                left_castling: false
            };
            let right = {
                right_castle: true,
                right_castling: false
            };
            const player_color = <?= json_encode(trim($player_num)); ?>;
            console.log('players color', player_color)

            let board = {
                0: ["♖", "♘", "♗", "♕", "♔", "♗", "♘", "♖"],
                1: ["♙", "♙", "♙", "♙", "♙", "♙", "♙", "♙"],
                2: ["", "", "", "", "", "", "", ""],
                3: ["", "", "", "", "", "", "", ""],
                4: ["", "", "", "", "", "", "", ""],
                5: ["", "", "", "", "", "", "", ""],
                6: ["♟", "♟", "♟", "♟", "♟", "♟", "♟", "♟"],
                7: ["♜", "♞", "♝", "♛", "♚", "♝", "♞", "♜"],
            };
            const id = <?= $_GET['id']; ?>;
            const player = String(<?= json_encode(trim($_GET['player'])); ?>);
            const socket = new WebSocket("ws://10.0.0.135:3001/");
            let join = 0;
            let turn = 1;
            async function init() {
                const response = await fetch('http://10.0.0.135:1234/games/init');
                const text = await response.text(); // or .json()
                //alert(text);
            }
            socket.addEventListener('open', event => {
                console.log('WebSocket connection established!');
                const payload = {
                    player: player,
                    id: id,
                    type: 'chess-join',
                    game: 'chess'
                }
                socket.send(JSON.stringify(payload));
            });
            socket.addEventListener('message', msg => {
                const data = JSON.parse(msg.data);
                console.log(data)
                //try {
                console.log(data.type, data.id, data.player)
                if (data.type === 'chess-join' && data.player !== player && id === data.id) {
                    alert('game start');
                } else if (data.type === 'chess-update' && data.id === id) {
                    updateBoard(board, data.move_array, left, right);
                    console.log(data.player, player, turn);
                    if (data.player !== player) {
                        turn = 1;
                    }
                }
                if (data.type === 'chess-update' && data.id === id && data.player !== player) {
                    console.log('turn++');
                    turn = 1;
                }

                /*} catch {
                    console.log('error with types from msg');
                }*/
            });


            console.log(board.length, board[0].length)
            let move_array = [-1, -1];
            for (let i = 0; i < board[0].length; i++) {
                for (let j = 0; j < board[0].length; j++) {
                    let cell = document.getElementById(String(i) + String(j));
                    if ((i + j) % 2 === 1) {
                        cell.style.backgroundColor = '#379efdb6';
                    } else {
                        cell.style.background = '#fffff0';
                    }
                    cell.textContent = board[i][j];

                    cell.addEventListener('click', () => {
                        console.log('click', i, j)
                        if (move_array[0] === -1) {
                            move_array[0] = String(i) + String(j);
                        } else {
                            move_array[1] = String(i) + String(j);
                            console.log('before legal check', move_array, turn);
                            if (legalMove(board, move_array, left, right) && turn === 1 &&
                                player_color === checkColor(move_array[0])) {
                                console.log('legal')
                                const update = {
                                    type: 'chess-update',
                                    game: 'chess',
                                    move_array: move_array,
                                    id: id,
                                    player: player,
                                    turn: turn,
                                }
                                turn = 0;
                                socket.send(JSON.stringify(update));
                            } else {
                                //alert('Invalid move');
                            }

                            move_array = [-1, -1];
                            //do something

                        }
                    })

                }
            }

            function castling(move_array, current_piece, left, right) {
                const black_left = ['71', '72', '73'];
                const black_right = ['75', '76'];
                const white_left = ['01', '02', '03'];
                const white_right = ['05', '06'];
                if (left.left_castling) {
                    if (black_left.includes(move_array[1])) {
                        black_left.forEach((spot) => {
                            if (checkColor(spot) !== '') {
                                console.log('castling not allowed')
                                return false;
                            }
                        })
                    } else {
                        white_left.forEach((spot) => {
                            if (checkColor(spot) !== '') {
                                console.log('castling not allowed')
                                return false;
                            }
                        })
                    }
                } else {
                    if (black_right.includes(move_array[1])) {
                        black_right.forEach((spot) => {
                            if (checkColor(spot) !== '') {
                                console.log('castling not allowed')
                                return false;
                            }
                        })
                    } else {
                        white_right.forEach((spot) => {
                            if (checkColor(spot) !== '') {
                                console.log('castling not allowed')
                                return false;
                            }
                        });
                    }
                    return true;

                }
            }

            function legalMove(board, move_array, left, right) {
                const current_piece = document.getElementById(move_array[0]).textContent;
                const end_space = document.getElementById(move_array[1]);

                let color = checkColor(move_array[0]);
                let collision_color;

                const white_double = ['10', '11', '12', '13', '14', '15', '16', '17'];
                const black_double = ['60', '61', '62', '63', '64', '65', '66', '67']
                console.log(color);
                const old = move_array[0].split("");
                const new_loc = move_array[1].split("");
                let o_x = parseInt(old[0]);
                let o_y = parseInt(old[1]);
                let n_x = parseInt(new_loc[0]);
                let n_y = parseInt(new_loc[1]);
                let diff_x = n_x - o_x;
                let diff_y = n_y - o_y;
                console.log(current_piece, left, right, diff_x, diff_y);

                if ((current_piece === '♚' || current_piece === '♔') && (Math.abs(diff_y) >= 2)) {
                    console.log('maybe castling')
                    if (left.left_castle || right.right_castle) {
                        if (move_array[1] === '72' || move_array[1] === '02') {
                            left.left_castling = true
                        } else if (move_array[1] === '76' || move_array[1] === '06') {
                            right.right_castling = true;
                        }
                    } else {
                        left.left_castling = false;
                        right.right_castling = false;
                        return false;
                    }
                } else {
                    left.left_castling = false;
                    right.right_castling = false;
                }
                if (left.left_castling || right.right_castling) {
                    console.log('checking castle validation');
                    return castling(move_array, current_piece, left.left_castling, left.right_castling);
                }
                console.log('move_array', move_array, new_loc)

                if (current_piece === '') {
                    return false;
                }
                if (diff_x === 0 && diff_y === 0) {
                    return false;
                }
                console.log('checkpoint 1')
                if ((current_piece === '♘' || current_piece === '♞') && Math.sqrt(Math.abs(Math.abs(o_x) - Math.abs(n_x)) ** 2 + Math.abs(Math.abs(o_y) - Math.abs(n_y)) ** 2) === 2.23606797749979) {
                    if (color === checkColor(move_array[1])) {
                        console.log('failed', move_array[1])
                        return false;
                    }
                    return true;
                } else if (current_piece === '♘' || current_piece === '♞') {
                    return false;
                }
                console.log('checkpoint 2')

                console.log(old[0], new_loc[0])
                if (current_piece === '♙') {
                    if (white_double.includes(move_array[0])) {
                        console.log('diff', diff_x, diff_y);
                        if (diff_x > 2) {
                            return false;
                        }
                    } else {
                        if (diff_x > 1) {
                            return false;
                        }
                    }
                }
                console.log('checkpoint 3')

                if (current_piece === '♟') {
                    if (black_double.includes(move_array[0])) {
                        console.log('diff', diff_x, diff_y);
                        if (diff_x < -2) {
                            return false;
                        }
                    } else {
                        if (diff_x < -1) {
                            return false;
                        }
                    }

                }
                console.log('checkpoint 4')

                if ((current_piece === '♙')) {
                    if (end_space.textContent !== '') {
                        console.log('piece found');
                        let count = 0;
                        if (o_x + 1 !== n_x) {
                            console.log('failed X')
                            return false;
                        }
                        if (color === checkColor(move_array[1])) {
                            console.log('failed color')
                            return false;
                        }
                        if (o_y !== n_y + 1 && o_y !== n_y - 1) {
                            console.log('failed Y')
                            return false;
                        }
                        return true;
                    } else if (old[1] !== new_loc[1]) {
                        console.log('invalid move')
                        return false;
                    }
                } else if (current_piece === '♟') {
                    if (end_space.textContent !== '') {
                        console.log('piece found');
                        let count = 0;
                        if (o_x - 1 !== n_x) {
                            console.log('failed X')
                            return false;
                        }
                        if (color === checkColor(move_array[1])) {
                            console.log('failed color')
                            return false;
                        }
                        if (o_y !== n_y - 1 && o_y !== n_y + 1) {
                            console.log('failed Y')
                            return false;
                        }
                        return true;
                    } else if (old[1] !== new_loc[1]) {
                        console.log('invalid move')
                        return false;
                    } else {
                        return true
                    }
                }
                console.log('checkpoint 5');
                /*
                if ((current_piece === '♙') && (old[1] !== new_loc[1] || o_x >= n_x)) {
                    if (old[1] !== new_loc[1] - 1 && old[0] - 1 !== new_loc[0] && color !== checkColor(move_array[1])) {
                        return false;
                    } else if (old[1] !== new_loc[1] && document.getElementById(move_array[1].textValue === '')) {
                        return false;
                    }
                }*/

                if ((current_piece === '♜' || current_piece === '♖') && ((diff_x !== 0 && diff_y !== 0) || (diff_x !== 0 && diff_y !== 0))) {

                    return false;
                }
                if ((current_piece === '♝' || current_piece === '♗') && Math.abs(diff_x) !== Math.abs(diff_y)) {
                    return false;
                }
                if ((current_piece === '♛' || current_piece === '♕') && ((Math.abs(diff_x) !== Math.abs(diff_y)) && ((diff_x !== 0 && diff_y !== 0) || (diff_x !== 0 && diff_y !== 0)))) {
                    return false;
                }
                console.log('checkpoint 6')
                while (diff_x !== 0 || diff_y !== 0) {
                    const new_piece = document.getElementById(String(o_x + diff_x) + String(o_y + diff_y)).textContent
                    if (new_piece !== null && new_piece !== '') {
                        if ((diff_x > 0 || diff_y > 0 || diff_x < 0 || diff_x < 0) && color === checkColor(String(o_x + diff_x) + String(o_y + diff_y)) && (current_piece !== '♙' || current_piece !== '♙')) {
                            console.log('checkpoint 6.1')
                            return false;
                        }
                    }
                    if (diff_x < 0) {
                        diff_x++;
                    } else if (diff_x > 0) {
                        diff_x--;
                    }
                    if (diff_y < 0) {
                        diff_y++;
                    } else if (diff_y > 0) {
                        diff_y--;
                    }
                    if (diff_x + o_x === o_x && diff_y + o_y === o_y) {
                        console.log('6.2')
                        return true;
                    }

                }
                console.log('checkpoint 7')
                if (move_array[0] === '07' && current_piece === '♖') {
                    right_castle = false;
                } else if (move_array[0] === '00' && current_piece === '♖') {
                    left_castle = false;
                } else if (move_array[0] === '70' && current_piece === '♜') {
                    left_castle = false;
                } else if (move_array[0] === '77' && current_piece === '♜') {
                    right_castle = false;
                }
                console.log('checkpoint 8')
                return true;

            }

            function checkColor(move) {
                const piece = document.getElementById(move).textContent;
                console.log(move);
                console.log('checkColor', piece)
                if (piece === '♙' || piece === '♘' || piece === '♗' || piece === '♖' || piece === '♔' || piece === '♕') {

                    return 'white';
                } else if (piece === '♟' || piece === '♞' || piece === '♝' || piece === '♜' || piece === '♚' || piece === '♛') {
                    return 'black';
                } else {
                    return '';
                }

            }

            function updateBoard(board, move_array, left, right) {
                const black_left = ['71', '72', '73'];
                const black_right = ['75', '76'];
                const white_left = ['01', '02', '03'];
                const white_right = ['05', '06'];
                console.log('updating board')
                if (left.left_castling) {
                    console.log('castling')
                    if (black_left.includes(move_array[1])) {
                        document.getElementById(move_array[1]).textContent = document.getElementById(move_array[0]).textContent;
                        document.getElementById('73').textContent = document.getElementById('70').textContent;
                        document.getElementById('70').textContent = '';
                    } else {
                        document.getElementById(move_array[1]).textContent = document.getElementById(move_array[0]).textContent;
                        document.getElementById('03').textContent = document.getElementById('00').textContent;
                        document.getElementById('00').textContent = '';
                    }
                } else if (right.right_castling) {
                    //console.log('castling')

                    if (black_right.includes(move_array[1])) {
                        console.log('black right');
                        document.getElementById('75').textContent = document.getElementById('77').textContent;
                        document.getElementById('77').textContent = '';
                    } else {
                        console.log('white right');
                        document.getElementById('05').textContent = document.getElementById('07').textContent;
                        document.getElementById('07').textContent = '';
                    }
                }
                const old_split = move_array[0].split("");
                const new_split = move_array[1].split("");
                board[parseInt(old_split[0])][parseInt(old_split[1])] = '';
                board[parseInt(new_split[0])][parseInt(new_split[1])] = document.getElementById(move_array[0]).textContent;
                document.getElementById(move_array[1]).textContent = document.getElementById(move_array[0]).textContent;
                document.getElementById(move_array[0]).textContent = '';
            }
        });
    </script>
</body>

</html>