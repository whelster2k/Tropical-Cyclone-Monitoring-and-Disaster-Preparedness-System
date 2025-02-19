<?php
$title = 'Hazard Assessment';
ob_start();
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Hazard Assessment</h1>

    <!-- Location Input Form -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']); ?>/hazards/assess" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="lat" class="block text-sm font-medium text-gray-700">Latitude</label>
                    <input type="number" step="0.000001" name="lat" id="lat" 
                           value="<?php echo isset($lat) ? htmlspecialchars($lat) : ''; ?>"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                </div>
                <div>
                    <label for="lng" class="block text-sm font-medium text-gray-700">Longitude</label>
                    <input type="number" step="0.000001" name="lng" id="lng"
                           value="<?php echo isset($lng) ? htmlspecialchars($lng) : ''; ?>"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Assess Location
                </button>
            </div>
        </form>
    </div>

    <?php if (isset($assessment)): ?>
        <!-- Assessment Results -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Assessment Results</h2>
            
            <!-- Overall Risk Score -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <span class="text-gray-700">Overall Risk Score:</span>
                    <span class="font-semibold 
                        <?php echo $assessment['normalized_score'] >= 75 ? 'text-red-600' :
                            ($assessment['normalized_score'] >= 50 ? 'text-orange-600' :
                            ($assessment['normalized_score'] >= 25 ? 'text-yellow-600' : 'text-green-600')); ?>">
                        <?php echo number_format($assessment['normalized_score'], 1); ?>%
                    </span>
                </div>
                <div class="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                    <div class="h-2.5 rounded-full 
                        <?php echo $assessment['normalized_score'] >= 75 ? 'bg-red-600' :
                            ($assessment['normalized_score'] >= 50 ? 'bg-orange-500' :
                            ($assessment['normalized_score'] >= 25 ? 'bg-yellow-500' : 'bg-green-500')); ?>"
                        style="width: <?php echo $assessment['normalized_score']; ?>%">
                    </div>
                </div>
            </div>

            <!-- Individual Risks -->
            <?php if (!empty($assessment['risks'])): ?>
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-800">Identified Hazards:</h3>
                    <?php foreach ($assessment['risks'] as $risk): ?>
                        <div class="bg-gray-50 rounded p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="font-medium"><?php echo ucfirst(htmlspecialchars($risk['type'])); ?></span>
                                    <span class="ml-2 px-2 py-1 text-sm rounded
                                        <?php echo $risk['risk_level'] === 'extreme' ? 'bg-red-100 text-red-800' :
                                            ($risk['risk_level'] === 'high' ? 'bg-orange-100 text-orange-800' :
                                            ($risk['risk_level'] === 'medium' ? 'bg-yellow-100 text-yellow-800' :
                                            'bg-green-100 text-green-800')); ?>">
                                        <?php echo ucfirst(htmlspecialchars($risk['risk_level'])); ?> Risk
                                    </span>
                                </div>
                                <span class="font-semibold">Score: <?php echo number_format($risk['score'], 1); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-600">No specific hazards identified at this location.</p>
            <?php endif; ?>

            <!-- Recommendations -->
            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-800 mb-2">Recommendations:</h3>
                <ul class="list-disc list-inside space-y-2 text-gray-600">
                    <?php if ($assessment['normalized_score'] >= 75): ?>
                        <li>Immediate evacuation may be necessary</li>
                        <li>Contact local authorities for assistance</li>
                        <li>Monitor official announcements closely</li>
                    <?php elseif ($assessment['normalized_score'] >= 50): ?>
                        <li>Prepare for possible evacuation</li>
                        <li>Keep emergency supplies ready</li>
                        <li>Stay informed about weather conditions</li>
                    <?php elseif ($assessment['normalized_score'] >= 25): ?>
                        <li>Review your emergency plans</li>
                        <li>Check emergency supplies</li>
                        <li>Monitor local news and updates</li>
                    <?php else: ?>
                        <li>No immediate action required</li>
                        <li>Stay informed about weather conditions</li>
                        <li>Maintain basic emergency preparedness</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require APP_ROOT . '/app/views/layouts/main.php';
?> 