require('dotenv').config();
const mysql = require('mysql2/promise');

// Create a connection pool
const pool = mysql.createPool(process.env.DATABASE_URL);

// Export the pool so it can be used in other files
module.exports = pool;
