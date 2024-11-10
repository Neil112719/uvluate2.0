import React, { useState } from 'react';
import axios from 'axios';

const AdminPage = () => {
    const [showUsers, setShowUsers] = useState(false); // Controls whether to display the user list
    const [showCreateUserForm, setShowCreateUserForm] = useState(false); // Controls whether to display the create user form
    const [users, setUsers] = useState([]);
    const [newUser, setNewUser] = useState({
        id_number: '',
        password: '',
        fname: '',
        middlename: '',
        lname: '',
        suffix: '',
        email: '',
        usertype: '4', // Default to "Student"
        department: '',
        course: '',
        section: '',
        year: ''
    });
    const [message, setMessage] = useState('');

    // Function to fetch users
    const fetchUsers = async () => {
        try {
            const response = await axios.get('http://localhost:8000/get_users.php', { withCredentials: true });
            if (response.data.status === 'success') {
                setUsers(response.data.users);
                setMessage(''); // Clear any previous messages
            } else {
                setMessage(response.data.message);
            }
        } catch (error) {
            setMessage('An error occurred while fetching users.');
        }
    };

    // Toggle showing the users list
    const handleShowUsers = () => {
        if (!showUsers) {
            fetchUsers();
        }
        setShowUsers(!showUsers);
    };

    // Toggle showing the create user form
    const handleShowCreateUserForm = () => {
        setShowCreateUserForm(!showCreateUserForm);
    };

    // Handle input changes in the create user form
    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setNewUser({ ...newUser, [name]: value });
    };

    // Handle user creation
    const handleCreateUser = async (e) => {
        e.preventDefault();
        try {
            const response = await axios.post(
                'http://localhost:8000/create_user.php',
                newUser,
                { headers: { 'Content-Type': 'application/json' }, withCredentials: true }
            );
            if (response.data.status === 'success') {
                setMessage(response.data.message);
                setShowCreateUserForm(false); // Hide the form after successful creation
                fetchUsers(); // Refresh the list of users after creation
            } else {
                setMessage(response.data.message);
            }
        } catch (error) {
            setMessage('An error occurred while creating the user.');
        }
    };

    return (
        <div className="admin-page">
            <h1>Admin Page</h1>
            <button onClick={handleShowUsers}>{showUsers ? 'Hide Users' : 'Show Users'}</button>
            <button onClick={handleShowCreateUserForm}>{showCreateUserForm ? 'Cancel' : 'Create User'}</button>

            {message && <p>{message}</p>}

            {/* Conditionally render user table */}
            {showUsers && users.length > 0 && (
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
                            <th>Course</th>
                            <th>Section</th>
                            <th>Year</th>
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
                                <td>{user.course}</td>
                                <td>{user.section}</td>
                                <td>{user.year}</td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            )}

            {/* Message for when no users are found */}
            {showUsers && users.length === 0 && <p>No users found.</p>}

            {/* Conditionally render create user form */}
            {showCreateUserForm && (
                <form onSubmit={handleCreateUser} className="create-user-form">
                    <h2>Create New User</h2>
                    <input
                        type="text"
                        name="id_number"
                        placeholder="ID Number"
                        value={newUser.id_number}
                        onChange={handleInputChange}
                        required
                    />
                    <input
                        type="password"
                        name="password"
                        placeholder="Password"
                        value={newUser.password}
                        onChange={handleInputChange}
                        required
                    />
                    <input
                        type="text"
                        name="fname"
                        placeholder="First Name"
                        value={newUser.fname}
                        onChange={handleInputChange}
                        required
                    />
                    <input
                        type="text"
                        name="middlename"
                        placeholder="Middle Name"
                        value={newUser.middlename}
                        onChange={handleInputChange}
                    />
                    <input
                        type="text"
                        name="lname"
                        placeholder="Last Name"
                        value={newUser.lname}
                        onChange={handleInputChange}
                        required
                    />
                    <input
                        type="text"
                        name="suffix"
                        placeholder="Suffix"
                        value={newUser.suffix}
                        onChange={handleInputChange}
                    />
                    <input
                        type="email"
                        name="email"
                        placeholder="Email"
                        value={newUser.email}
                        onChange={handleInputChange}
                        required
                    />
                    <select
                        name="usertype"
                        value={newUser.usertype}
                        onChange={handleInputChange}
                        required
                    >
                        <option value="1">Admin</option>
                        <option value="2">Dean</option>
                        <option value="3">Coordinator</option>
                        <option value="4">Student</option>
                    </select>
                    <input
                        type="text"
                        name="department"
                        placeholder="Department"
                        value={newUser.department}
                        onChange={handleInputChange}
                    />
                    <input
                        type="text"
                        name="course"
                        placeholder="Course"
                        value={newUser.course}
                        onChange={handleInputChange}
                    />
                    <input
                        type="text"
                        name="section"
                        placeholder="Section"
                        value={newUser.section}
                        onChange={handleInputChange}
                    />
                    <input
                        type="number"
                        name="year"
                        placeholder="Year"
                        value={newUser.year}
                        onChange={handleInputChange}
                    />
                    <button type="submit">Create User</button>
                </form>
            )}
        </div>
    );
};

export default AdminPage;
