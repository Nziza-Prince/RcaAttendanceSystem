<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center bg-gradient-primary justify-content-center" href="index.php">
        <div class="sidebar-brand-icon">
            <img src="img/logo.png">
        </div>
        <div class="sidebar-brand-text mx-3">RCA Teacher's Attendance</div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item active">
        <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Attendances
    </div>
    <li class="nav-item">
        <a class="nav-link" href="takeAttendance.php">
            <i class="fas fa-calendar-check"></i>
            <span>Take Attendance</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="adminview.php">
            <i class="fas fa-calendar-check"></i>
            <span>View Daily Class Attendance</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="adminviewweekly.php">
            <i class="fas fa-calendar-week"></i>
            <span>View By Date Range</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Class and Class Rooms
    </div>
    <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap"
            aria-expanded="true" aria-controls="collapseBootstrap">
            <i class="fas fa-chalkboard"></i>
            <span>Manage Classes</span>
        </a>
        <div id="collapseBootstrap" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Classes</h6>
                <a class="collapse-item" href="../classes.php">Add a classroom</a>
            </div>
        </div>
    </li> -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrapusers"
            aria-expanded="true" aria-controls="collapseBootstrapusers">
            <i class="fas fa-code-branch"></i>
            <span>Manage Class Rooms</span>
        </a>

        <div id="collapseBootstrapusers" class="collapse" aria-labelledby="headingBootstrap"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Class Rooms</h6>
                <a class="collapse-item" href="createClassArms.php">Add more classrooms</a>
            </div>
        </div>
    </li>
    <!-- <hr class="sidebar-divider"> -->
    <!-- <div class="sidebar-heading">
        Students
    </div>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap2" aria-expanded="true" aria-controls="collapseBootstrap2">
            <i class="fas fa-user-graduate"></i>
            <span>Manage Students</span>
        </a>
        <div id="collapseBootstrap2" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Students</h6>
                <a class="collapse-item" href="createStudents.php">Create Students</a>
            </div>
        </div>
    </li> -->


    <div class="sidebar-heading">
        Class Monitors
    </div>
    <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseClassTeachers"
            aria-expanded="true" aria-controls="collapseClassTeachers">
            <i class="fas fa-users"></i>
            <span>Manage Class Monitors</span>
        </a>
        <div id="collapseClassTeachers" class="collapse" aria-labelledby="headingBootstrap"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Class Monitors</h6>
                <a class="collapse-item" href="createClassTeacher.php">Create Class Monitor</a>
            </div>
        </div>
    </li>
    <hr class="sidebar-divider"> -->


    <li>
        <hr class="sidebar-divider">
        <div class="sidebar-heading">
            Session & Term
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrapcon"
            aria-expanded="true" aria-controls="collapseBootstrapcon">
            <i class="fa fa-calendar-alt"></i>
            <span>Manage Session & Term</span>
        </a>
        <div id="collapseBootstrapcon" class="collapse" aria-labelledby="headingBootstrap"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Contribution</h6>
                <a class="collapse-item" href="createSessionTerm.php">Create Session and Term</a>
                <!-- <a class="collapse-item" href="addMemberToContLevel.php ">Add Member to Level</a> -->
            </div>
        </div>
    </li>
    <hr class="sidebar-divider">
</ul>