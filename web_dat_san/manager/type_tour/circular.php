<div class="flex justify-center gap-6 w-full">
    <div class="team_a w-full">
        <h2 class="text-center font-bold text-xl mb-5 mt-5">BẢNG A</h2>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        STT
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Đội
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Điểm
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Đội trưởng
                    </th>
                    <th scope="col" class="px-1 py-3">
                        SDT
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Bảng
                    </th>

                </tr>
            </thead>
            <tbody>
                <?php
                $index = 1;
                if ($groupTeam_A) {
                    foreach ($groupTeam_A as $teamA) {

                        echo '   <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                ' . $index++ . '
                                </th>
                                
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                ' . $teamA['t_name'] . '
                                </th>
                                
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                ' . $teamA['point'] . '
                                </th>
                                
                                
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                ' . $teamA['cus_name'] . '
                                </th>
                                
                                <th scope="row" class="px-1 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                ' . $teamA['phone'] . '
                                </th>
                                
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                ' . $teamA['group'] . '
                                </th>
                                </tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    
    <div class="team_b w-full">
        <h2 class="text-center font-bold text-xl mb-5 mt-5">BẢNG B</h2>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">

            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        STT
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Đội
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Điểm
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Đội trưởng
                    </th>
                    <th scope="col" class="px-1 py-3">
                        SDT
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Bảng
                    </th>

                </tr>
            </thead>
            <tbody>
                <?php
                if ($groupTeam_B) {
                    foreach ($groupTeam_B as $teamB) {

                        echo '   <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            ' . $index++ . '
                            </th>
                            
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            ' . $teamB['t_name'] . '
                            </th>
                            
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            ' . $teamB['point'] . '
                            </th>
                            
                            
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            ' . $teamB['cus_name'] . '
                            </th>
                            
                            <th scope="row" class="px-1 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            ' . $teamB['phone'] . '
                            </th>
                            
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            ' . $teamB['group'] . '
                            </th>
                            </tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

</div>