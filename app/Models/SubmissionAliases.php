<?php

namespace App\Models;

/**
 * This file provides compatibility between the old Submission model 
 * and the new AssignmentSubmission model to avoid errors in existing code.
 */

// Create a class alias if needed
if (!class_exists('App\Models\AssignmentSubmission')) {
    class_alias('App\Models\Submission', 'App\Models\AssignmentSubmission');
}

// Or, if the AssignmentSubmission class exists but the Submission class doesn't:
if (!class_exists('App\Models\Submission')) {
    class_alias('App\Models\AssignmentSubmission', 'App\Models\Submission');
}
