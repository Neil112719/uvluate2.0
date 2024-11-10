import React, { useState, useEffect } from 'react';
import axios from 'axios';

const AdminPage = () => {
    const [showCreateUserForm, setShowCreateUserForm] = useState(false);
    const [showEditUserForm, setShowEditUserForm] = useState(false);
    const [users, setUsers] = useState([]);
    const [message, setMessage] = useState('');
    const [editingUser, setEditingUser] = useState(null);

    // Fetch users from the server
    const fetchUsers = async () => {
        try {
            const response = await axios.get('http://localhost:8000/get_users.php', { withCredentials: true });
            if (response.data.status === 'success') {
                setUsers(response.data.users);
            } else {
                setMessage(response.data.message);
            }
        } catch (error) {
            setMessage('An error occurred while fetching users.');
        }
    };

    // Toggle Create User Form
    const toggleCreateUserForm = () => {
        setShowCreateUserForm(!showCreateUserForm);
    };

    // Delete a user
    const handleDeleteUser = async (id_number) => {
        try {
            const response = await axios.post(
                'http://localhost:8000/delete_user.php',
                { id_number },
                { headers: { 'Content-Type': 'application/json' }, withCredentials: true }
            );
            if (response.data.status === 'success') {
                setMessage(response.data.message);
                fetchUsers(); // Refresh the list after deletion
            } else {
                setMessage(response.data.message);
            }
        } catch (error) {
            setMessage('An error occurred while deleting the user.');
        }
    };

    // Load the user data into the form for editing
    const handleEditUser = (user) => {
        setEditingUser(user);
        setShowEditUserForm(true);
    };

    // Update user information
    const handleUpdateUser = async (e) => {
        e.preventDefault();
        try {
            const response = await axios.post(
                'http://localhost:8000/update_user.php',
                editingUser,
                { headers: { 'Content-Type': 'application/json' }, withCredentials: true }
            );
            if (response.data.status === 'success') {
                setMessage(response.data.message);
                setShowEditUserForm(false); // Close the form on success
                fetchUsers(); // Refresh the list after update
            } else {
                setMessage(response.data.message);
            }
        } catch (error) {
            setMessage('An error occurred while updating the user.');
        }
    };

    // Handle input changes in the edit form
    const handleEditInputChange = (e) => {
        const { name, value } = e.target;
        setEditingUser({ ...editingUser, [name]: value });
    };

    // Show All Users button
    const handleShowAllUsers = () => {
        fetchUsers();
    };

    return (
        <div className="admin-page">
            <h1>Admin Page</h1>
            <button onClick={toggleCreateUserForm}>Create User</button>
            <button onClick={handleShowAllUsers}>Show All Users</button>

            {showCreateUserForm && (
                <form /* Include form fields as discussed in previous steps */>
                    {/* Form fields and submit button */}
                </form>
            )}

            {message && <p>{message}</p>}

            {/* User Table */}
            {users.length > 0 && (
                <table>
                    <thead>
                        <tr>
                            <th>ID Number</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Last Name</th>
                            <th>Suffix</th>
                            <th>Email</th>
                            <th>User Type</th>
                            <th>Department</th>
                            <th>Section</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {users.map((user) => (
                            <tr key={user.id_number}>
                                <td>{user.id_number}</td>
                                <td>{user.fname}</td>
                                <td>{user.middlename}</td>
                                <td>{user.lname}</td>
                                <td>{user.suffix}</td>
                                <td>{user.email}</td>
                                <td>{user.usertype}</td>
                                <td>{user.department}</td>
                                <td>{user.section}</td>
                                <td>
                                    <button onClick={() => handleEditUser(user)}>Edit</button>
                                    <button onClick={() => handleDeleteUser(user.id_number)}>Delete</button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            )}

            {/* Edit User Form */}
            {showEditUserForm && editingUser && (
                <form onSubmit={handleUpdateUser}>
                    <h2>Edit User</h2>
                    <input
                        type="text"
                        name="fname"
                        placeholder="First Name"
                        value={editingUser.fname}
                        onChange={handleEditInputChange}
                    />
                    <input
                        type="text"
                        name="middlename"
                        placeholder="Middle Name"
                        value={editingUser.middlename}
                        onChange={handleEditInputChange}
                    />
                    <input
                        type="text"
                        name="lname"
                        placeholder="Last Name"
                        value={editingUser.lname}
                        onChange={handleEditInputChange}
                    />
                    <input
                        type="text"
                        name="suffix"
                        placeholder="Suffix"
                        value={editingUser.suffix}
                        onChange={handleEditInputChange}
                    />
                    <input
                        type="email"
                        name="email"
                        placeholder="Email"
                        value={editingUser.email}
                        onChange={handleEditInputChange}
                    />
                    <select name="usertype" value={editingUser.usertype} onChange={handleEditInputChange}>
                        <option value="1">Admin</option>
                        <option value="2">Dean</option>
                        <option value="3">Coordinator</option>
                        <option value="4">Student</option>
                    </select>
                    <input
                        type="text"
                        name="department"
                        placeholder="Department"
                        value={editingUser.department}
                        onChange={handleEditInputChange}
                    />
                    <input
                        type="text"
                        name="section"
                        placeholder="Section"
                        value={editingUser.section}
                        onChange={handleEditInputChange}
                    />
                    <button type="submit">Update User</button>
                </form>
            )}
        </div>
    );
};

export default AdminPage;
