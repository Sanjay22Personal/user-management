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
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
            Add User
        </button>

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
                    <th>ID</th>
                    <th>Name</th>
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
                            <button onclick="openEditViewUserModal(${user.id}, 'view')">View</button>
                            <button onclick="openEditViewUserModal(${user.id})">Edit</button>
                            <button onclick="deleteUser(${user.id})">Delete</button>
                        </center>
                        </td>
                    </tr>
                `;
                
            });
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

    </script>
</body>
</html>

