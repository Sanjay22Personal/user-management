<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <center><h1>User Management</h1></center>

        <!-- Button to open Add User modal -->
        <p align='right'>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
            Add User
        </button>
        </p>

        <!-- Add User Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Form for adding records -->
                        <form id="addUserForm" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="address">Address:</label>
                                <input type="text" class="form-control" id="address" name="address" required>
                            </div>
                            <div class="form-group">
                                <label for="gender">Gender:</label>
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="image">Image:</label>
                                <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit/View User Modal -->
        <div class="modal fade" id="editViewUserModal" tabindex="-1" role="dialog" aria-labelledby="editViewUserModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editViewUserModalLabel">User Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Form fields for editing user details -->
                        <form id="editViewUserForm">
                            <input type="hidden" id="editViewUserId" name="editViewUserId">
                            <div class="form-group">
                                <label for="editViewName">Name:</label>
                                <input type="text" class="form-control" id="editViewName" name="editViewName" required>
                            </div>
                            <div class="form-group">
                                <label for="editViewAddress">Address:</label>
                                <input type="text" class="form-control" id="editViewAddress" name="editViewAddress" required>
                            </div>
                            <div class="form-group">
                                <label for="editViewGender">Gender:</label>
                                <select class="form-control" id="editViewGender" name="editViewGender" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <!-- Buttons for saving changes or closing the modal -->
                                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                                <button type="submit" class="btn btn-primary" id="editViewUserAction">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display existing records -->
        <h2>Existing Users</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>
                        ID
                        <button class="btn btn-sm btn-outline-secondary" onclick="sortUsers('id')">Sort</button>
                    </th>
                    <th>
                        Name
                        <!-- Sorting button for Name -->
                        <button class="btn btn-sm btn-outline-secondary" onclick="sortUsers('name')">Sort</button>
                    </th>
                    <th>Image</th>
                    <th>Gender</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="userTable">
                <!-- Existing records will be dynamically added here -->
            </tbody>
        </table>
        
        <!-- Message for no users added -->
        <div id="noUsersMessage" class="alert alert-info" style="display: block;">
            <center>Oops! It looks like there are no users added yet. Start by adding a new user using the 'Add User' button above.</center>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript for adding and deleting records -->
    <script>
        // Array to store user data
        let users = [];

        // Function to render user table
        function renderUserTable() {
            const tableBody = document.getElementById('userTable');
            tableBody.innerHTML = '';
            if (users.length === 0) {
            // If no users are added, display the message
                document.getElementById('noUsersMessage').style.display = 'block';
            } else {
                // If users are added, hide the message and render table rows
                document.getElementById('noUsersMessage').style.display = 'none';
                users.forEach(user => {
                    const imageUrl = user.image ? URL.createObjectURL(user.image) : ''; // Get image URL if available
                    tableBody.innerHTML += `
                        <tr id="user_${user.id}">
                            <td>${user.id}</td>
                            <td>${user.name}</td>
                            <td><img src="${imageUrl}" width="100" height="100"></td>
                            <td>${user.address}</td>
                            <td>${user.gender}</td>
                            <td>
                                <center>
                                    <button onclick="openEditViewUserModal(${user.id}, 'view')" class="button button1">View</button>
                                    <button onclick="openEditViewUserModal(${user.id})" class="button button2">Edit</button>
                                    <button onclick="deleteUser(${user.id})" class="button button3">Delete</button>
                                </center>
                            </td>
                        </tr>
                    `;
                    
                });
            }
        }

        // Function to sort users based on attribute (id or name)
        function sortUsers(attribute) {
            // Sort users array based on the selected attribute
            users.sort((a, b) => {
                if (attribute === 'id') {
                    return a.id - b.id;
                } else if (attribute === 'name') {
                    return a.name.localeCompare(b.name);
                }
            });
            // Re-render the user table with sorted data
            renderUserTable();
        }

        // Function to add new user
        document.getElementById('addUserForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const name = document.getElementById('name').value;
            const address = document.getElementById('address').value;
            const gender = document.getElementById('gender').value;
            const image = document.getElementById('image').files[0];
            const newUser = {
                id: users.length + 1, // Generate ID
                name: name,
                address: address,
                gender: gender,
                image: image
            };
            users.push(newUser);
            renderUserTable();
            alert('User added successfully.');
            // Close the modal
            $('#addUserModal').modal('hide');
            // Clear form fields if needed
            document.getElementById('addUserForm').reset();
        });
        
        // Function to open the edit/view user modal
        function openEditViewUserModal(userId, mode) {
            // Find user by ID
            const user = users.find(user => user.id === userId);
            if (user) {
                // Update modal title
                document.getElementById('editViewUserModalLabel').textContent = (mode === 'view') ? 'View User' : 'Edit User';
                // Update form fields with user details
                document.getElementById('editViewUserId').value = user.id;
                document.getElementById('editViewName').value = user.name;
                document.getElementById('editViewAddress').value = user.address;
                document.getElementById('editViewGender').value = user.gender;
                // Update button label and action based on mode
                const actionButton = document.getElementById('editViewUserAction');
                if (mode === 'view') {
                    actionButton.textContent = 'Close';
                    actionButton.classList.add('btn-secondary');
                    // Disable form fields in view mode
                    document.getElementById('editViewName').disabled = true;
                    document.getElementById('editViewAddress').disabled = true;
                    document.getElementById('editViewGender').disabled = true;
                } else {
                    actionButton.textContent = 'Save Changes';
                    actionButton.classList.remove('btn-secondary');
                    // Enable form fields in edit mode
                    document.getElementById('editViewName').disabled = false;
                    document.getElementById('editViewAddress').disabled = false;
                    document.getElementById('editViewGender').disabled = false;
                }
                // Show the edit/view user modal
                $('#editViewUserModal').modal('show');
            }
        }

        // Function to handle form submission (for editing user details)
        document.getElementById('editViewUserForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const userId = document.getElementById('editViewUserId').value;
            const name = document.getElementById('editViewName').value;
            const address = document.getElementById('editViewAddress').value;
            const gender = document.getElementById('editViewGender').value;
            // Update user details in users array
            const index = users.findIndex(user => user.id === parseInt(userId));
            if (index !== -1) {
                users[index].name = name;
                users[index].address = address;
                users[index].gender = gender;
                renderUserTable();
                if (document.getElementById('editViewUserAction').textContent === 'Save Changes') {
                    alert('User details updated successfully.');
                }
                // Close the edit/view user modal
                $('#editViewUserModal').modal('hide');
            }
        });

        // Function to handle modal close (for view mode)
        $('#editViewUserModal').on('hide.bs.modal', function () {
            // Clear form fields
            document.getElementById('editViewUserId').value = '';
            document.getElementById('editViewName').value = '';
            document.getElementById('editViewAddress').value = '';
            document.getElementById('editViewGender').value = 'Male'; // Reset gender to default
        });

        // Function to delete user
        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                const index = users.findIndex(user => user.id === userId);
                if (index !== -1) {
                    users.splice(index, 1);
                    renderUserTable();
                    alert('User deleted successfully.');
                }
            }
        }
    </script>
</body>
</html>

<style>
    table {
    border-collapse: collapse;
    width: 100%;
    }

    th, td {
    text-align: left;
    padding: 8px;
    }

    tr:nth-child(even){background-color: #f2f2f2}

    th {
    background-color: #04AA6D;
    color: white;
    }

    .button {
    background-color: #04AA6D; /* Green */
    border: none;
    color: white;
    padding: 16px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    transition-duration: 0.4s;
    cursor: pointer;
    }

    .button1 {
    background-color: white; 
    color: black; 
    border: 3px solid #04AA6D;
    border-radius: 4px;
    }

    .button1:hover {
    background-color: #04AA6D;
    color: white;
    }

    .button2 {
    background-color: white; 
    color: black; 
    border: 2px solid #008CBA;
    border-radius: 4px;
    }

    .button2:hover {
    background-color: #008CBA;
    color: white;
    }

    .button3 {
    background-color: white; 
    color: black; 
    border: 2px solid #f44336;
    border-radius: 4px;
    }

    .button3:hover {
    background-color: #f44336;
    color: white;
    }

    input[type=text], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    }

    input[type=submit] {
    width: 100%;
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    }

    input[type=submit]:hover {
    background-color: #45a049;
    }

    div {
    border-radius: 5px;
    background-color: #f2f2f2;
    }
</style>
