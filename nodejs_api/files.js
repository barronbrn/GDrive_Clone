const express = require('express');
const db = require('./db'); // Import the new DB connection
const authenticateToken = require('./middleware/auth');

const router = express.Router();

// Apply authentication middleware to all file routes
router.use(authenticateToken);

// GET all files for the authenticated user
router.get('/', async (req, res) => {
  let { parent_id } = req.query;
  const userId = req.user.userId;

  // Handle root folder case where parent_id is not provided
  if (parent_id === undefined) {
      parent_id = null;
  }

  try {
    const query = 'SELECT * FROM files WHERE created_by = ? AND parent_id <=> ? AND deleted_at IS NULL ORDER BY is_folder DESC, name ASC';
    const [files] = await db.execute(query, [userId, parent_id]);
    res.json(files);
  } catch (error) {
    console.error(error);
    res.status(500).json({ message: 'Internal server error' });
  }
});

// GET a single file by ID
router.get('/:id', async (req, res) => {
    const { id } = req.params;
    const userId = req.user.userId;

    try {
        const query = 'SELECT * FROM files WHERE id = ? AND created_by = ? AND deleted_at IS NULL';
        const [rows] = await db.execute(query, [id, userId]);
        const file = rows[0];

        if (!file) {
            return res.status(404).json({ message: 'File not found' });
        }

        res.json(file);
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Internal server error' });
    }
});

// DELETE (soft) a file by ID
router.delete('/:id', async (req, res) => {
    const { id } = req.params;
    const userId = req.user.userId;

    try {
        // Soft delete the file by setting the deleted_at timestamp
        const query = 'UPDATE files SET deleted_at = ? WHERE id = ? AND created_by = ?';
        const [result] = await db.execute(query, [new Date(), id, userId]);

        if (result.affectedRows === 0) {
            return res.status(404).json({ message: 'File not found or you do not have permission to delete it' });
        }

        res.status(204).send(); // No Content

    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Internal server error' });
    }
});

module.exports = router;