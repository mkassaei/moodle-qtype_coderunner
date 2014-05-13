<?php
/** The EqualityGrader class. Compares the output from a given test case,
 *  awarding full marks if and only if the output exactly matches the expected
 *  output. Otherwise, zero marks are awarded. The output is deemed to match
 *  the expected if the two are byte for byte identical after trailing white
 *  space has been removed from both.
 *  "Trailing white space" means all white space at the end of the strings
 *  plus all white space from the end of each line in the strings. It does
 *  not include blank lines within the strings or white space within the lines.
 */

/**
 * @package    qtype
 * @subpackage coderunner
 * @copyright  Richard Lobb, 2013, The University of Canterbury
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('graderbase.php');
class EqualityGrader extends Grader {

    /** Called to grade the output generated by a student's code for
     *  a given testcase. Returns a single TestResult object.
     *  Should not be called if the execution failed (syntax error, exception
     *  etc).
     */
    function gradeKnownGood(&$output, &$testCase) {
        $cleanedOutput = Grader::clean($output);
        $cleanedExpected = Grader::clean($testCase->expected);
        $isCorrect = $cleanedOutput == $cleanedExpected;
        $awardedMark = $isCorrect ? $testCase->mark : 0.0;

        if ($testCase->stdin) {
            $resultStdin = Grader::tidy($testCase->stdin);
        } else {
            $resultStdin = NULL;
        }

        return new TestResult(
                Grader::tidy($testCase->testcode),
                $testCase->mark,
                $isCorrect,
                $awardedMark,
                Grader::tidy($cleanedExpected),
                Grader::tidy($cleanedOutput),
                $resultStdin
        );
    }
}