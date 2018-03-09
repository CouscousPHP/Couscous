<?php

namespace Couscous\Module\Markdown\Step;

use Couscous\Model\Project;
use Couscous\Module\Markdown\Model\MarkdownFile;
use Couscous\Step;

/**
 * Set the base url dynamically.
 *
 * @author Moritz Marquardt <git@mo-mar.de>
 */
class SetBaseUrl implements Step
{
	public function __invoke(Project $project)
	{
		if ($project->metadata['baseUrl'] === NULL || $project->metadata['baseUrl'] == '.') {

			/** @var MarkdownFile[] $markdownFiles */
			$markdownFiles = $project->findFilesByType('Couscous\Module\Markdown\Model\MarkdownFile');

			foreach ($markdownFiles as $file) {
				$baseUrl = ".";
				$depth = substr_count(ltrim($file->relativeFilename, "/"), "/");
				while ($depth > 0) {
					$baseUrl .= "/..";
					$depth--;
				}
				if ($baseUrl != '.') $baseUrl = substr($baseUrl, 2);

				$file->getMetadata()->setMany([ 'baseUrl' => $baseUrl ]);
			}
		}
	}
}
