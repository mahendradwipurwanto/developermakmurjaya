<?php

date_default_timezone_set('Asia/Jakarta');

require_once '../helpers/functions.php';

function webhook($data = [], $webhook_data = [])
{
    $extractedData = array(
        'issue.key' => $data['issue.key'] ?? '',
        'issue.assignee.displayName' => $data['issue.assignee.displayName'] ?? '',
        'issue.summary' => $data['issue.summary'] ?? '',
        'issue.reporter.displayName' => $data['issue.reporter.displayName'] ?? '',
        'issue.status.name' => $data['issue.status.name'] ?? '',
        'issue.url' => $data['issue.url'] ?? '',
        'issue.QA.displayName' => $data['issue.QA.displayName'] ?? '',
        'issue.Lead / Reviewers.displayName' => $data['issue.Lead / Reviewers.displayName'] ?? '',
        'issue.duedate' => $data['issue.duedate'] ?? '',
        'issue.Story Points estimate' => $data['issue.Story Points estimate'] ?? '',
        'issue.issueType.name' => $data['issue.issueType.name'] ?? '',
        'issue.project.name' => $data['issue.project.name'] ?? ''
    );

    $template = $webhook_data['template'];

    foreach ($extractedData as $key => $value) {

        $template = replaceAttrJira($extractedData, $template, $webhook_data);

        if ($webhook_data['type'] == "assignee") {
            if ($key == 'issue.assignee.displayName') {
                $template = str_replace('{{issue.google.id}}', mentionUsers($value), $template);
            }
        }

        if ($webhook_data['type'] == "review") {
            if ($key == 'issue.Lead / Reviewers.displayName') {
                $template = str_replace('{{issue.google.id}}', mentionUsers($value), $template);
            }
        }

        if ($webhook_data['type'] == "testing") {
            if ($key == 'issue.QA.displayName') {
                $template = str_replace('{{issue.google.id}}', mentionUsers($value), $template);
            }
        }

        $template = str_replace('{{' . $key . '}}', $value, $template);
    }

    logs(replaceTemplate($extractedData), $webhook_data['project'], 'assignee');

    return curlGoogleChat($webhook_data['url'], $template);
}