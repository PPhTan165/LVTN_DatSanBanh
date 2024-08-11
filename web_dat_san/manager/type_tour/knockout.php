

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
                            Đội trưởng
                        </th>
                        <th scope="col" class="px-1 py-3">
                            SDT
                        </th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($team_of_tournament) {
                        $index = 1;
                        foreach ($team_of_tournament as $team) {

                            echo '   <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">

                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            ' . $index++ . '
                        </th>

                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            ' . $team['t_name'] . '
                        </th>
   
   
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            ' . $team['cus_name'] . '
                        </th>

                        <th scope="row" class="px-1 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            ' . $team['phone'] . '
                        </th>

                    </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>