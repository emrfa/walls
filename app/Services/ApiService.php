<?php

namespace App\Services;

class ApiService
{
    private function paginate($items, $page = 1, $perPage = 10, $filters = [], $sortField = null, $sortDirection = 'asc')
    {
        $collection = collect($items);

        // Filtering
        if (!empty($filters)) {
            $collection = $collection->filter(function ($item) use ($filters) {
                foreach ($filters as $key => $value) {
                    if (empty($value)) continue;
                    // Handle nested or specific fields if necessary, for now simple string match
                    if (!isset($item[$key])) continue;
                    if (stripos((string)$item[$key], (string)$value) === false) return false;
                }
                return true;
            });
        }

        // Sorting
        if ($sortField) {
            $collection = $sortDirection === 'asc' 
                ? $collection->sortBy($sortField) 
                : $collection->sortByDesc($sortField);
        }

        $total = $collection->count();
        $lastPage = max((int)ceil($total / $perPage), 1);
        $page = max(min($page, $lastPage), 1);
        
        $data = $collection->forPage($page, $perPage)->values()->all();

        return [
            'data' => $data,
            'meta' => [
                'current_page' => (int)$page,
                'last_page' => $lastPage,
                'per_page' => (int)$perPage,
                'total' => $total,
            ],
        ];
    }

    public function getCommands($page = 1, $perPage = 10, $filters = [], $sortField = 'code', $sortDirection = 'asc')
    {
        // Initialize session data if not exists
        if (!session()->has('commands')) {
            $initialData = [
                [
                    'id' => 1,
                    'device_module' => 'AV',
                    'code' => '100',
                    'command' => 'Set mask FB Open',
                    'set_reset' => 'Set',
                    'opposite' => '101',
                    'category' => 'Mask',
                ],
                [
                    'id' => 2,
                    'device_module' => 'AV',
                    'code' => '101',
                    'command' => 'Reset mask FB Open',
                    'set_reset' => 'Reset',
                    'opposite' => '100',
                    'category' => 'Mask',
                ],
                [
                    'id' => 3,
                    'device_module' => 'AV',
                    'code' => '102',
                    'command' => 'Set mask FB Close',
                    'set_reset' => 'Set',
                    'opposite' => '103',
                    'category' => 'Mask',
                ],
                [
                    'id' => 4,
                    'device_module' => 'AV',
                    'code' => '103',
                    'command' => 'Reset mask FB Close',
                    'set_reset' => 'Reset',
                    'opposite' => '102',
                    'category' => 'Mask',
                ],
                [
                    'id' => 5,
                    'device_module' => 'AV',
                    'code' => '140',
                    'command' => 'Set SIM IO',
                    'set_reset' => 'Set',
                    'opposite' => '141',
                    'category' => 'SIM',
                ],
                [
                    'id' => 6,
                    'device_module' => 'AV',
                    'code' => '141',
                    'command' => 'Reset SIM IO',
                    'set_reset' => 'Reset',
                    'opposite' => '140',
                    'category' => 'SIM',
                ],
            ];
            session(['commands' => $initialData]);
        }

        $data = session('commands');

        return $this->paginate($data, $page, $perPage, $filters, $sortField, $sortDirection);
    }

    public function getCommand($id)
    {
        $commands = session('commands', []);
        return collect($commands)->firstWhere('id', $id);
    }

    public function storeCommand($data)
    {
        $commands = session('commands', []);
        $newId = count($commands) > 0 ? max(array_column($commands, 'id')) + 1 : 1;
        
        $newItem = array_merge(['id' => $newId], $data);
        $commands[] = $newItem;
        
        session(['commands' => $commands]);
        return $newItem;
    }

    public function updateCommand($id, $data)
    {
        $commands = session('commands', []);
        foreach ($commands as &$command) {
            if ($command['id'] == $id) {
                $command = array_merge($command, $data);
                session(['commands' => $commands]);
                return $command;
            }
        }
        return null;
    }

    public function deleteCommand($id)
    {
        $commands = session('commands', []);
        $commands = array_filter($commands, function ($command) use ($id) {
            return $command['id'] != $id;
        });
        
        session(['commands' => array_values($commands)]);
        return true;
    }

    public function getCategories($page = 1, $perPage = 10, $filters = [], $sortField = 'code', $sortDirection = 'asc')
    {
        if (!session()->has('categories')) {
            $initialData = [
                [
                    'id' => 1,
                    'code' => '1',
                    'name' => 'Mask',
                ],
                [
                    'id' => 2,
                    'code' => '2',
                    'name' => 'SIM',
                ],
                [
                    'id' => 3,
                    'code' => '3',
                    'name' => 'Set Value',
                ],
            ];
            session(['categories' => $initialData]);
        }

        $data = session('categories');

        return $this->paginate($data, $page, $perPage, $filters, $sortField, $sortDirection);
    }

    public function getCategory($id)
    {
        $categories = session('categories', []);
        return collect($categories)->firstWhere('id', $id);
    }

    public function storeCategory($data)
    {
        $categories = session('categories', []);
        $newId = count($categories) > 0 ? max(array_column($categories, 'id')) + 1 : 1;
        
        $newItem = array_merge(['id' => $newId], $data);
        $categories[] = $newItem;
        
        session(['categories' => $categories]);
        return $newItem;
    }

    public function updateCategory($id, $data)
    {
        $categories = session('categories', []);
        foreach ($categories as &$category) {
            if ($category['id'] == $id) {
                $category = array_merge($category, $data);
                session(['categories' => $categories]);
                return $category;
            }
        }
        return null;
    }

    public function deleteCategory($id)
    {
        $categories = session('categories', []);
        $categories = array_filter($categories, function ($category) use ($id) {
            return $category['id'] != $id;
        });
        
        session(['categories' => array_values($categories)]);
        return true;
    }

    public function getUsers($page = 1, $perPage = 10, $filters = [], $sortField = 'nip', $sortDirection = 'asc')
    {
        if (!session()->has('users')) {
            $initialData = [
                [
                    'id' => 1,
                    'nip' => '1',
                    'name' => 'Joko',
                    'password' => '••••••',
                    'permissions' => ['view_dashboard', 'manage_users', 'view_commands', 'edit_commands'],
                ],
                [
                    'id' => 2,
                    'nip' => '2',
                    'name' => 'Kabir',
                    'password' => '••••••',
                    'permissions' => ['view_dashboard', 'view_commands'],
                ],
                [
                    'id' => 3,
                    'nip' => '3',
                    'name' => 'Fulan',
                    'password' => '••••••',
                    'permissions' => ['view_dashboard'],
                ],
            ];
            session(['users' => $initialData]);
        }

        $data = session('users');

        return $this->paginate($data, $page, $perPage, $filters, $sortField, $sortDirection);
    }

    public function getUser($id)
    {
        $users = session('users', []);
        return collect($users)->firstWhere('id', $id);
    }

    public function storeUser($data)
    {
        $users = session('users', []);
        $newId = count($users) > 0 ? max(array_column($users, 'id')) + 1 : 1;
        
        $newItem = array_merge(['id' => $newId], $data);
        $users[] = $newItem;
        
        session(['users' => $users]);
        return $newItem;
    }

    public function updateUser($id, $data)
    {
        $users = session('users', []);
        foreach ($users as &$user) {
            if ($user['id'] == $id) {
                $user = array_merge($user, $data);
                session(['users' => $users]);
                return $user;
            }
        }
        return null;
    }

    public function deleteUser($id)
    {
        $users = session('users', []);
        $users = array_filter($users, function ($user) use ($id) {
            return $user['id'] != $id;
        });
        
        session(['users' => array_values($users)]);
        return true;
    }

    public function getRoles()
    {
        return [
            [
                'name' => 'admin',
                'permissions' => ['view_dashboard', 'manage_users', 'view_commands', 'edit_commands']
            ],
            [
                'name' => 'operator',
                'permissions' => ['view_dashboard', 'view_commands']
            ],
            [
                'name' => 'viewer',
                'permissions' => ['view_dashboard']
            ],
        ];
    }

    public function getTimeline($page = 1, $perPage = 10, $filters = [], $sortField = 'set_time', $sortDirection = 'desc')
    {
        $data = [
            [
                'id' => 1,
                'tag_id' => '413285',
                'area' => 'MP1',
                'command' => 'Mask FB Open',
                'airpressure' => '5.0 bar',
                'set_time' => '2025-12-01 10:32:00',
                'reset_time' => '2025-12-01 11:32:00',
                'duration' => '1H:2M:00S',
                'user' => 'Administrator',
                'node' => 'MIXSC-01',
                'status' => 'Pending',
                'reasons' => [
                    [
                        'date' => '7 DEC 2025',
                        'time' => '10:42 pm',
                        'user_name' => 'Joko',
                        'user_role' => '',
                        'message' => 'Sensor Rusak',
                        'actions' => ['Ganti Sensor', 'Kalibrasi Ulang'],
                        'used_parts' => ['Sensor', 'Kabel 2m'],
                        'status' => 'Pending',
                    ],
                    [
                        'date' => '7 DEC 2025',
                        'time' => '10:33 pm',
                        'user_name' => 'Kabir',
                        'user_role' => '',
                        'message' => 'Sensor Kejauhan',
                        'actions' => ['Adjust Posisi'],
                        'used_parts' => ['Sensor'],
                        'status' => 'Open',
                    ]
                ]
            ],
            [
                'id' => 2,
                'tag_id' => '413203',
                'area' => 'MP2',
                'command' => 'Mask FB Close',
                'airpressure' => '6.0 bar',
                'set_time' => '2025-12-01 11:32:00',
                'reset_time' => '2025-12-11 11:32:00',
                'duration' => '240H:56M:00S',
                'user' => 'Administrator',
                'node' => 'MIXSC-01',
                'status' => 'Open',
                'reasons' => []
            ],
        ];

        return $this->paginate($data, $page, $perPage, $filters, $sortField, $sortDirection);
    }

    public function getDeviceModules($page = 1, $perPage = 10, $filters = [], $sortField = 'tag_code', $sortDirection = 'asc')
    {
        // Initialize session data if not exists
        if (!session()->has('device_modules')) {
            $initialData = [
                [
                    'id' => 1,
                    'tag_code' => '41',
                    'device_module' => 'AV',
                    'description' => 'Automatic Valve',
                ],
                [
                    'id' => 2,
                    'tag_code' => '42',
                    'device_module' => 'AM',
                    'description' => 'Automatic Motor',
                ],
                [
                    'id' => 3,
                    'tag_code' => '3',
                    'device_module' => 'EM',
                    'description' => 'Equipment Module',
                ],
                [
                    'id' => 4,
                    'tag_code' => '2',
                    'device_module' => 'PL',
                    'description' => 'Programm Logic',
                ],
            ];
            session(['device_modules' => $initialData]);
        }

        $data = session('device_modules');

        return $this->paginate($data, $page, $perPage, $filters, $sortField, $sortDirection);
    }

    public function getDeviceModule($id)
    {
        $modules = session('device_modules', []);
        return collect($modules)->firstWhere('id', $id);
    }

    public function storeDeviceModule($data)
    {
        $modules = session('device_modules', []);
        $newId = count($modules) > 0 ? max(array_column($modules, 'id')) + 1 : 1;
        
        $newItem = array_merge(['id' => $newId], $data);
        $modules[] = $newItem;
        
        session(['device_modules' => $modules]);
        return $newItem;
    }

    public function updateDeviceModule($id, $data)
    {
        $modules = session('device_modules', []);
        foreach ($modules as &$module) {
            if ($module['id'] == $id) {
                $module = array_merge($module, $data);
                session(['device_modules' => $modules]);
                return $module;
            }
        }
        return null;
    }

    public function deleteDeviceModule($id)
    {
        $modules = session('device_modules', []);
        $modules = array_filter($modules, function ($module) use ($id) {
            return $module['id'] != $id;
        });
        
        session(['device_modules' => array_values($modules)]);
        return true;
    }

    public function getCurrentUser()
    {
        // Mocking the logged-in user as the first user (Admin)
        // We access the raw data array directly here since we don't have a database
        $users = $this->getUsers(1, 1)['data']; 
        $userData = $users[0];
        // Ensure password exists if it was missing in session data
        if (!isset($userData['password'])) {
            $userData['password'] = 'password';
        }
        return new \App\Models\User($userData);
    }
}
