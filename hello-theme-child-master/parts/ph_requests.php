<?php 
$candidateInProgress = get_query_var('candidateInProgress');
                               
    if($candidateInProgress) { // sended Requests                                    
    ?>
<div class="flex w-full flex-col gap-4 overflow-y-auto  p-2 reqCards-scroll h-full">

    <?php foreach ($candidateInProgress as $cand) { 
        $sex = get_post_meta($cand['candidate_id'], '_sex', true);
        $procedure = ($sex == 'male') ? 'Seeking Vasectomy' : 'Tubal Ligation';
        $age = get_post_meta($cand['candidate_id'], '_age', true);
        $location = get_post_meta($cand['candidate_id'], '_location', true);

        global $wpdb;
        $table_name = $wpdb->prefix . 'jet_cct_zipcodes';
        $query = $wpdb->prepare("SELECT * FROM $table_name WHERE zipcode = %s", $location);
        $row = $wpdb->get_row($query);
        $distance = sprintf('%s, %s', $row->city, $row->state_code);
        
        $raised_amount = intval(get_post_meta($cand['candidate_id'], '_amount_raised', true));
        $goal = intval(get_post_meta($cand['candidate_id'], '_goal', true));
        //var_dump($goal);
        ?>


    <div class="w-full flex flex-col px-4 py-3 rounded-lg gap-4 shadowCard">
        <div class="flex justify-between">
            <div class="data">
                <div class="flex w-full items-center flex-wrap gap-4">
                    <h2 class="text-lg text-primary font-bold">
                        <?php echo $cand['candidate']; ?></h2>
                    <?php 
                                                        switch ($sex) {
                                                            case 'male':
                                                                echo '<span class="py-1 px-2 border rounded-full" style="font-family: Be Vietnam Pro; font-size: 12px; font-style: normal; font-weight: 500; line-height: 150%; color: #0B46A0;  background: #EDF3FE;">Seeking Vasectomy</span>';
                                                                break;
                                                            case 'female':
                                                                echo '<span class="py-1 px-2 border rounded-full" style="font-family: Be Vietnam Pro; font-size: 12px; font-style: normal;font-weight: 500; line-height: 150%; color: #8802A9; background: #F3E6F6; white-space: nowrap;">Seeking Tubal Ligation</span>';
                                                                break;
                                                            default:
                                                                echo '';
                                                            break;
                                                        }

                                                        switch ($sex) {
                                                            case 'male':
                                                                echo '<span class="py-1 px-2 border rounded-full" style="font-family: Be Vietnam Pro; font-size: 12px; font-style: normal; font-weight: 500; line-height: 150%; color: #0B46A0;  background: #EDF3FE;"><i class="fas fa-mars mr-2"></i> Male</span>';
                                                                break;
                                                            case 'female':
                                                                echo '<span class="py-1 px-2 border rounded-full" style="font-family: Be Vietnam Pro; font-size: 12px; font-style: normal;font-weight: 500; line-height: 150%; color: #8802A9;  background: #F3E6F6; white-space: nowrap;"><i class="fas fa-venus mr-2"></i>Female</span>';
                                                                break;
                                                            default:
                                                                echo '';
                                                            break;
                                                        }
                                                        ?>
                    <span class="py-1 px-2 border rounded-full flex"
                        style="font-family: Be Vietnam Pro; font-size: 12px; font-style: normal;font-weight: 500; line-height: 150%; color: #76787A;  background: #EBEBEB; white-space: nowrap;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none"
                            class="mr-2">
                            <g id="Component 1" clip-path="url(#clip0_424_12987)">
                                <path id="Vector"
                                    d="M13.5 2.5H3.5C3.22386 2.5 3 2.72386 3 3V13C3 13.2761 3.22386 13.5 3.5 13.5H13.5C13.7761 13.5 14 13.2761 14 13V3C14 2.72386 13.7761 2.5 13.5 2.5Z"
                                    stroke="#003366" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                                <path id="Vector_2" d="M11.5 1.5V3.5" stroke="#003366" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                                <path id="Vector_3" d="M5.5 1.5V3.5" stroke="#003366" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                                <path id="Vector_4" d="M3 5.5H14" stroke="#003366" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                            </g>
                            <defs>
                                <clipPath id="clip0_424_12987">
                                    <rect width="16" height="16" fill="white" transform="translate(0.5)">
                                    </rect>
                                </clipPath>
                            </defs>
                        </svg>
                        <?php echo $age;?>years old</span>
                    <span class="py-1 px-2 border rounded-full flex items-center"
                        style="font-family: Be Vietnam Pro; font-size: 12px; font-style: normal;font-weight: 500; line-height: 150%; color: #76787A;  background: #EBEBEB; white-space: nowrap;">
                        <i aria-hidden="true" class="fas fa-map-marker-alt mr-2"
                            style="color:#003366"></i><?php echo $distance;?></span>

                </div>

                <div class="flex flex-col gap-2 w-full xl:max-w-md mt-4">

                    <progress class="progress progress-primary w-full"
                        value="<?php echo ($raised_amount / $goal) * 100; ?>" max="100"></progress>

                </div>
                <div class="flex w-full justify-between flex-wrap gap-4 xl:max-w-md mt-2">
                    <span class="text-sm font-medium text-textColor">$<?php echo $raised_amount; ?>
                        raised</span>
                    <span class="text-sm font-medium text-textValue">of
                        $<?php echo $goal; ?></span>
                </div>
            </div>

            <div class="flex gap-3 items-center">

                <button
                    class="px-4 py-2 bg-primary rounded-xl text-white border-primary hover:scale-105 approve_candidate"
                    data-candidate-id="<?php echo $cand['candidate_id'];?>">Approve</button>
                <button
                    class="px-4 py-2 bg-white border-warning rounded-xl text-warning hover:scale-105 hover:bg-red-200 hover:border-red-200 hover:text-red-500 decline_candidate"
                    data-candidate-id="<?php echo $cand['candidate_id'];?>">Decline</button>
            </div>

        </div>



    </div>
    <?php }; ?>
</div>
<?php
                                    } else {
                                        ?>
<div class="text-center text-2xl text-textValue py-5 w-full">No request</div>
<?php
                                    }
                                ?>