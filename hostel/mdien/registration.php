<?php
$pageTitle = 'Register Student';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/sidebar.php';

// --- Handle POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = getDB();

    $fields = ['roomno','seater','feespm','foodstatus','stayfrom','duration',
               'course','regno','fname','mname','lname','gender',
               'contactno','emailid','emcntno','gurname','gurrelation','gurcntno',
               'caddress','ccity','cstate','cpincode',
               'paddress','pcity','pstate','ppincode'];

    $data = [];
    foreach ($fields as $f) {
        $data[$f] = sanitize($_POST[$f] ?? '');
    }

    // Check duplicate
    $stmt = $db->prepare("SELECT COUNT(*) FROM registration WHERE emailid=? OR regno=?");
    $stmt->bind_param('ss', $data['emailid'], $data['regno']);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        redirect('registration.php', 'Email or Registration No. already exists.', 'error');
    }

    $sql = "INSERT INTO registration
        (roomno,seater,feespm,foodstatus,stayfrom,duration,course,regno,
         firstName,middleName,lastName,gender,contactno,emailid,egycontactno,
         guardianName,guardianRelation,guardianContactno,
         corresAddress,corresCIty,corresState,corresPincode,
         pmntAddress,pmntCity,pmnatetState,pmntPincode)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmt = $db->prepare($sql);
    $stmt->bind_param('iiiisisissssisississsisssi',
        $data['roomno'],  $data['seater'],  $data['feespm'],    $data['foodstatus'],
        $data['stayfrom'],$data['duration'],$data['course'],    $data['regno'],
        $data['fname'],   $data['mname'],   $data['lname'],     $data['gender'],
        $data['contactno'],$data['emailid'],$data['emcntno'],   $data['gurname'],
        $data['gurrelation'],$data['gurcntno'],
        $data['caddress'],$data['ccity'],   $data['cstate'],    $data['cpincode'],
        $data['paddress'],$data['pcity'],   $data['pstate'],    $data['ppincode']
    );
    $stmt->execute();
    $stmt->close();

    // Create user account (default pass = contact no)
    $stmt2 = $db->prepare("INSERT INTO userregistration
        (regNo,firstName,middleName,lastName,gender,contactNo,email,password)
        VALUES (?,?,?,?,?,?,?,?)");
    $stmt2->bind_param('sssssiss',
        $data['regno'], $data['fname'], $data['mname'], $data['lname'],
        $data['gender'], $data['contactno'], $data['emailid'], $data['contactno']
    );
    $stmt2->execute();
    $stmt2->close();

    redirect('manage-students.php', 'Student registered successfully!');
}
?>

<?php require_once '../includes/layout/sidebar.php'; ?>

<main class="flex-1 p-8">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Register New Student</h2>
        <?= flashMessage() ?>

        <form method="POST" class="space-y-8">

            <!-- Room Info -->
            <section class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-base font-semibold text-indigo-700 mb-4 border-b pb-2">
                    <i class="fa-solid fa-door-open mr-2"></i>Room Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <?php
                    $db = getDB();
                    $rooms = $db->query("SELECT roomno, seater FROM rooms");
                    ?>
                    <div>
                        <label class="form-label">Room No</label>
                        <select name="roomno" class="form-input" required>
                            <option value="">-- Select Room --</option>
                            <?php while ($r = $rooms->fetch_object()): ?>
                                <option value="<?= $r->roomno ?>"><?= $r->roomno ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Seater</label>
                        <input type="number" name="seater" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Food Status</label>
                        <select name="foodstatus" class="form-input" required>
                            <option value="1">With Food</option>
                            <option value="0">Without Food</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Monthly Fees (₫)</label>
                        <input type="number" name="feespm" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Stay From</label>
                        <input type="date" name="stayfrom" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Duration (months)</label>
                        <input type="number" name="duration" class="form-input" required>
                    </div>
                </div>
            </section>

            <!-- Personal Info -->
            <section class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-base font-semibold text-indigo-700 mb-4 border-b pb-2">
                    <i class="fa-solid fa-user mr-2"></i>Personal Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">First Name</label>
                        <input type="text" name="fname" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="mname" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Last Name</label>
                        <input type="text" name="lname" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Registration No.</label>
                        <input type="text" name="regno" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-input" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Course</label>
                        <select name="course" class="form-input" required>
                            <option value="">-- Select Course --</option>
                            <?php
                            $courses = getDB()->query("SELECT id, course_sn FROM courses");
                            while ($c = $courses->fetch_object()):
                            ?>
                                <option value="<?= $c->id ?>"><?= sanitize($c->course_sn) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Contact No.</label>
                        <input type="tel" name="contactno" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="emailid" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Emergency Contact</label>
                        <input type="tel" name="emcntno" class="form-input">
                    </div>
                </div>
            </section>

            <!-- Guardian Info -->
            <section class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-base font-semibold text-indigo-700 mb-4 border-b pb-2">
                    <i class="fa-solid fa-people-roof mr-2"></i>Guardian Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Guardian Name</label>
                        <input type="text" name="gurname" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Relation</label>
                        <input type="text" name="gurrelation" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Guardian Contact</label>
                        <input type="tel" name="gurcntno" class="form-input">
                    </div>
                </div>
            </section>

            <!-- Address -->
            <section class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-base font-semibold text-indigo-700 mb-4 border-b pb-2">
                    <i class="fa-solid fa-location-dot mr-2"></i>Address
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php
                    $addressGroups = [
                        'Correspondence Address' => ['caddress','ccity','cstate','cpincode'],
                        'Permanent Address'       => ['paddress','pcity','pstate','ppincode'],
                    ];
                    $labels = ['Address','City','State','Pincode'];
                    foreach ($addressGroups as $title => $names):
                    ?>
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-2"><?= $title ?></p>
                        <div class="space-y-3">
                            <?php foreach ($names as $i => $name): ?>
                            <input type="text" name="<?= $name ?>"
                                   placeholder="<?= $labels[$i] ?>"
                                   class="form-input">
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <div class="flex justify-end gap-3">
                <a href="manage-students.php"
                   class="px-6 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition">
                    <i class="fa-solid fa-save mr-2"></i>Register Student
                </button>
            </div>
        </form>
    </div>
</main>

<style>
.form-label { @apply block text-sm font-medium text-gray-700 mb-1; }
.form-input  { @apply w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent; }
</style>

<?php require_once __DIR__ . '/footer.php'; ?>