#!/usr/bin/env python3
from pathlib import Path
import json
import re
import subprocess
import unittest

ROOT = Path(__file__).resolve().parents[2]
WORKFLOW = ROOT / ".github" / "workflows" / "release.yml"


class ReleaseWorkflowTests(unittest.TestCase):
    def test_release_workflow_is_gated_and_recoverable(self):
        text = WORKFLOW.read_text(encoding="utf-8")
        self.assertIn("workflow_run:", text)
        self.assertIn("workflow_dispatch:", text)
        self.assertIn("default: master", text)
        self.assertIn("REQUESTED_REF: ${{ inputs.ref || 'master' }}", text)
        self.assertIn("Release automation simulation", text)
        self.assertIn("RELEASE_AUTOMATION_ENABLED: ${{ vars.RELEASE_AUTOMATION_ENABLED", text)
        self.assertIn('[ "$RELEASE_AUTOMATION_ENABLED" = "true" ]', text)
        self.assertIn("Candidate is not the current default-branch commit", text)
        self.assertIn("conclusion == 'success'", text)
        self.assertIn('actions/runs?per_page=100', text)
        self.assertIn('.path == ".github/workflows/ci.yml"', text)
        self.assertNotIn('gh run list --repo "$GITHUB_REPOSITORY" --workflow ci.yml', text)
        self.assertIn("fetch-tags: true", text)
        self.assertIn("release_guard.py", text)
        self.assertIn("create-tag-and-release", text)
        self.assertIn("needs.plan.outputs.action != 'none'", text)
        guard_text = (ROOT / ".github" / "ci" / "release_guard.py").read_text(encoding="utf-8")
        self.assertIn('"create-release"', guard_text)
        self.assertIn("git show-ref --verify", text)
        self.assertIn(".object.type", text)
        self.assertIn("git/tags/$remote_sha", text)
        self.assertNotIn("git tag -f", text)
        self.assertNotIn("git push --force", text)
        self.assertIn("CENTRAL_DISPATCH_ENABLED", text)
        self.assertIn("CENTRAL_DISPATCH_TOKEN", text)
        self.assertIn('Authorization: Bearer $CENTRAL_DISPATCH_TOKEN', text)
        self.assertNotIn('$CENTR...OKEN', text)
        self.assertIn("repos/Flobul/.jeedom/dispatches", text)
        self.assertIn('"folder": "jeedom-hdsentinel"', text)
        self.assertIn("actions/upload-artifact@v6", text)
        self.assertNotIn("pull_request_target", text)

    def test_release_baseline_matches_reference_1_02_01_commit(self):
        baseline = json.loads((ROOT / ".github" / "release-baseline.json").read_text(encoding="utf-8"))
        self.assertEqual(baseline["plugin_id"], "hdsentinel")
        self.assertEqual(baseline["class_file"], "core/class/hdsentinel.class.php")
        self.assertEqual(baseline["reference_version"], "1.02.01")
        self.assertEqual(
            baseline["reference_commit"],
            "1bde97de336fef4198ffd59f7bd4e843b2e9c990",
        )
        commit = subprocess.run(
            ["git", "-C", str(ROOT), "cat-file", "-t", baseline["reference_commit"]],
            check=True,
            text=True,
            capture_output=True,
        ).stdout.strip()
        self.assertEqual(commit, "commit")
        info = subprocess.run(
            ["git", "-C", str(ROOT), "show", f'{baseline["reference_commit"]}:plugin_info/info.json'],
            check=True,
            text=True,
            capture_output=True,
        ).stdout
        self.assertEqual(json.loads(info)["pluginVersion"], "1.02.01")
        class_text = subprocess.run(
            [
                "git",
                "-C",
                str(ROOT),
                "show",
                f'{baseline["reference_commit"]}:core/class/hdsentinel.class.php',
            ],
            check=True,
            text=True,
            capture_output=True,
        ).stdout
        self.assertRegex(
            class_text,
            r"public\s+static\s+\$_hdsentinelVersion\s*=\s*['\"]1\.02\.01['\"]",
        )

    def test_changelog_reconciles_all_historical_sources(self):
        changelog = (ROOT / "docs" / "CHANGELOG.md").read_text(encoding="utf-8")
        self.assertEqual(len(re.findall(r"^## Version ", changelog, re.MULTILINE)), 10)
        self.assertEqual(len(re.findall(r"^[-*+] ", changelog, re.MULTILINE)), 39)
        self.assertEqual(len(re.findall(r"(?<!!)\[[^\]]+\]\([^)]+\)|<(?:https?://|mailto:)[^>]+>", changelog)), 2)
        self.assertEqual(len(re.findall(r"!\[[^\]]*\]\([^)]+\)", changelog)), 1)
        self.assertIn("## Version 1.03.0 (14/10/2026)", changelog)
        self.assertIn("## Version 0.40 (18/01/2022)", changelog)
        self.assertIn("Première version", changelog)

    def test_no_tag_or_release_is_created_by_the_baseline(self):
        tags = subprocess.run(
            ["git", "-C", str(ROOT), "tag", "--list"],
            check=True,
            text=True,
            capture_output=True,
        ).stdout.splitlines()
        self.assertEqual(tags, [])

    def test_release_guard_is_part_of_blocking_ci(self):
        text = (ROOT / ".github" / "ci" / "extra-checks.sh").read_text(encoding="utf-8")
        self.assertIn("test_release_guard.py", text)
        workflow = (ROOT / ".github" / "workflows" / "check-jeedom.yml").read_text(encoding="utf-8")
        self.assertIn("fetch-depth: 0", workflow)
        orchestrator = (ROOT / ".github" / "workflows" / "ci.yml").read_text(encoding="utf-8")
        self.assertIn("name: CI", orchestrator)
        self.assertIn("uses: ./.github/workflows/check-jeedom.yml", orchestrator)
        self.assertIn("push:", orchestrator)
        self.assertIn("branches:", orchestrator)
        self.assertIn("master", orchestrator)
        self.assertNotIn("alpha", orchestrator)

    def test_central_dispatch_targets_current_repository(self):
        text = WORKFLOW.read_text(encoding="utf-8")
        config = json.loads((ROOT / ".github" / "ci" / "ci-config.json").read_text())
        expected = config["plugin"]["repository"]
        self.assertIn(f'"folder": "{expected}"', text)


if __name__ == "__main__":
    unittest.main(verbosity=2)
