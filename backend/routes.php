<?php

// assign endpoints to their respective file location and allowed methods

// login, signup, logout, profile_save,profile_fetch image_upload and reset_password routes
$router->endpoint('login', './views/login', ['POST'], FALSE, ['phone_number', 'password']);
$router->endpoint('signup', './views/signup', ['POST'], FALSE, ['phone_number', 'password', 'email', 'full_name']);
$router->endpoint('logout', './views/logout', ['POST'], FALSE, ['phone_number']);
$router->endpoint('reset_password', './views/reset_password', ['POST'], FALSE, ['old_password', 'new_password', 'phone_number']);
$router->endpoint('profile_save', './views/profile_save', ['POST'], FALSE, []);
$router->endpoint('profile_fetch', './views/profile_fetch', ['GET'], FALSE, []);
$router->endpoint('image_upload', './views/image_upload', ['POST'], FALSE, []);

// markets/manage_markets all routes
$router->endpoint('fetch_market', './views/markets/manage_markets/fetch_market', ['GET'], FALSE, []);
$router->endpoint('add_market', './views/markets/manage_markets/add_market', ['POST'], FALSE, ['market_fullname', 'market_opentime', 'market_closetime']);
$router->endpoint('edit_market', './views/markets/manage_markets/edit_market', ['POST'], FALSE, ['market_fullname', 'market_opentime', 'market_closetime', 'market_id']);
$router->endpoint('delete_market', './views/markets/manage_markets/delete_market', ['POST'], FALSE, ['market_id']);

// games/game_rates all routes
$router->endpoint('fetch_game_rates', './views/markets/game_rates/fetch_game_rates', ['GET'], FALSE, []);
$router->endpoint('edit_game_rate', './views/markets/game_rates/edit_game_rate', ['POST'], FALSE, ['game_rate', 'rate_id']);

// fetching betting history
$router->endpoint('bid_history', './views/markets/bid_history', ['GET'], FALSE, []);
$router->endpoint('win_history', './views/markets/win_history', ['GET'], FALSE, []);

// result publishing routes
$router->endpoint('fetch_result', './views/markets/results/fetch_result', ['GET'], FALSE, []);
$router->endpoint('add_result', './views/markets/results/add_result', ['POST'], FALSE, ['market_id', 'results']);
$router->endpoint('edit_result', './views/markets/results/edit_result', ['POST'], FALSE, ['result_id', 'results']);
$router->endpoint('delete_result', './views/markets/results/delete_result', ['POST'], FALSE, ['result_id']);

// customers details related routes
$router->endpoint('fetch_customers', './views/customers/fetch_customers', ['GET'], FALSE, []);
$router->endpoint('edit_customers', './views/customers/edit_customers', ['POST'], FALSE, ['user_id']);
$router->endpoint('delete_customers', './views/customers/delete_customers', ['POST'], FALSE, ['user_id']);


// admin_user details related routes
$router->endpoint('fetch_admin_user', './views/admin_user/fetch_admin_user', ['GET'], FALSE, []);
$router->endpoint('edit_admin_user', './views/admin_user/edit_admin_user', ['POST'], FALSE, []);
$router->endpoint('delete_admin_user', './views/admin_user/delete_admin_user', ['POST'], FALSE, ['admin_user_id']);


//track_live games
$router->endpoint('fetch_livegames', './views/track_livegames/fetch_livegames', ['GET'], []);
$router->endpoint('fetch_market_details', './views/track_livegames/fetch_market_details', ['POST'],FALSE, ['market_id']);

//payments
//deposit routes
$router->endpoint('save_deposit', './views/payments/deposit/save_deposit', ['POST'],FALSE, ['user_id', 'deposit_amount']); 
$router->endpoint('fetch_deposits', './views/payments/deposit/fetch_deposits', ['GET'],FALSE, []);

//withdraw_routes
$router->endpoint('fetch_withdraws', './views/payments/withdraw/fetch_withdraws', ['GET'],FALSE, []);
$router->endpoint('save_withdraws', './views/payments/withdraw/save_withdraws', ['POST'],FALSE, ['user_id', 'withdrawal_amount']); 

//dashboard 
$router->endpoint('dashboard', './views/dashboard', ['GET'], FALSE, []);

//referral
$router->endpoint('affiliate', './views/affiliate', ['GET'], FALSE, []);
