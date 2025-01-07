Database Structure

Users
    id                  - Primary Key
    username            - String (unique)
    name                - String
    email               - String (unique)
    password            - String
    profile_picture     - String (nullable)
    is_active           - Boolean (false)
    is_verification     - Boolean (false)

Roles
    id                  - Primary Key
    role_name           - String (unique)
    display_name        - String
    description         - Text (nullable)
    is_active           - Boolean
    type_role           - String

Permissions
    id                  - Primary Key
    menu_id             - Foreign Key (menus.id)
    name                - String (unique)
    permission_action   - String                    (view, create, update, delete dll...)
    description         - Text (nullable)

Menus
    id                  - Primary Key
    menu_name           - String (unique)
    path                - String (nullable) / String (same like menu_acstion.url)
    key                 - String (unique)
    parent_id           - Foreign Key (nullable) nested menus
    ordering            - Integer
    menu_icon           - String (nullable)

========================================================================================== Many to Many

Role_users (Pivot Table)
    id                  - Primary Key
    role_id             - Foreign Key (roles.id)
    user_id             - Foreign Key (users.id)
    is_active           - Boolean (false)

========================================================================================== Many to Many

Role_menus (Pivot Table)
    id                  - Primary Key
    role_id             - Foreign Key (roles.id)
    menu_id             - Foreign Key (menus.id)
    permission_id       - Foreign Key (permissions.id)
    is_allowed          - Boolean


#* One to One (Model::class, foreign_key, local_key)
    # Urutannya:

    # foreign_key: Field di model target.
    # local_key: Field di model saat ini.

# Penjelasan relasi 1 to 1 itu mungkin biasanya ditandai dengan adanya user_id di dalam DB untuk profile, makannya untuk memanggil datanya dapat dengan membuat method relation seperti ini atau mencari langsung dengan where berdasarkan id.
    public function profile()
    {
        # user memiliki 1 profile Profile ditandai dengan user_id
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }

    public function user()
    {
        # profile dimiliki oleh user dengan id dari user_id
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

#* One to Many (Model::class, foreign_key, local_key)
    # Urutannya:

    # foreign_key: Field di model target.
    # local_key: Field di model saat ini.

# Penjelasan relasi 1 to N itu mungkin seperti sebuah postingan bisa saja memiliki banyak komentar, ini juga biasanya ditandai dengan adanya post_id di dalam DB untuk setiap comment nya, makannya untuk memanggil datanya dapat juga dengan membuat method relation seperti ini atau mencari semua datanya langsung dengan where berdasarkan id.
    public function comments()
    {
        # post memiliki banyak Comment ditandai dengan post_id
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

    public function post()
    {
        # setiap comment dimiliki oleh post dengan id dari post_id
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

#* Many to Many 
    # Urutannya:

    # foreign_key: Field di tabel pivot yang merujuk ke model saat ini.
    # related_key: Field di tabel pivot yang merujuk ke model target.

# Penjelasan relasi N to N itu mungkin akan cukup panjang. Anggap saja kamu punya data users dan data roles, setiap user bisa memiliki banyak role, oleh karena itu dibutuhkannya database baru (pivot table) untuk menampung data keduanya, kenapa begini karena kita tidak bisa menyisipkan user_id di role dan role_id di user cara ini tidak akan memenuhi relasi N to N.
# Pivot table akan berisi role_user, yaitu id, role_id dan user_id. Pemanggilan relasi N to N dengan belongsToMany atau dimiliki oleh banyak, jadi misal user dengan id 1 bisa dimiliki / memiliki banyak (many) role nah untuk tablenya wajib disisipkan yaitu role_users.
# Parameter N to N - Model target, nama_tabel(pivot), foreignId, localId
    public function roles()
    {
        # 1 user bisa memiliki banyak role (User)
        return $this->belongsToMany(Role::class, 'role_users', 'user_id', 'role_id');
    }

    public function users()
    {
        # 1 role bisa dimiliki banyak user (Role)
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }

#* kasus lain, bagaimana dengan role_menu_permissions ? cukup ingat urutan untuk kasus Many to Many
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'role_menu_permissions', 'role_id', 'menu_id')
                    ->withPivot('permission_id');
    }

    User
	-> asign role
	[user_id, role_id]

Role
	-> asign permission
	[role_id, permission_id]	-> add role_menu_permission
					[role_id, menu_id, permission_id]

Menu
	
Permission
	-> asign menu
	[menu_id, permission_id]