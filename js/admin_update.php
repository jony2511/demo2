<?php
// Create a temporary file with the new content
$temp_content = <<<EOT
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    function activateTab(tabId) {
        // Hide all tabs and remove active class from buttons
        tabContents.forEach(tab => tab.style.display = 'none');
        tabButtons.forEach(btn => btn.classList.remove('active'));

        // Show selected tab and activate button
        document.getElementById(tabId).style.display = 'block';
        document.querySelector(`[data-tab="${tabId}"]`).classList.add('active');

        // Save active tab to localStorage
        localStorage.setItem('activeAdminTab', tabId);
    }

    // Add click event to each tab button
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabId = button.getAttribute('data-tab');
            activateTab(tabId);
        });
    });

    // Show last active tab or default to messages
    const activeTab = localStorage.getItem('activeAdminTab') || 'messages-tab';
    activateTab(activeTab);

    // Icon preview functionality for add form
    const iconClassInput = document.getElementById('icon_class');
    const iconPreview = document.getElementById('icon_preview');

    if (iconClassInput && iconPreview) {
        iconClassInput.addEventListener('input', function() {
            iconPreview.innerHTML = `<i class="${this.value}"></i>`;
        });
    }

    // Icon preview functionality for edit form
    const editIconClassInput = document.getElementById('edit_icon_class');
    const editIconPreview = document.getElementById('edit_icon_preview');

    if (editIconClassInput && editIconPreview) {
        editIconClassInput.addEventListener('input', function() {
            editIconPreview.innerHTML = `<i class="${this.value}"></i>`;
        });
    }

    // Edit skill functionality
    const editSkillButtons = document.querySelectorAll('.edit-skill-btn');
    const editSkillForm = document.getElementById('edit-skill-form');
    const addSkillForm = document.getElementById('add-skill-form');

    editSkillButtons.forEach(button => {
        button.addEventListener('click', function() {
            const skillId = this.getAttribute('data-id');
            const skillName = this.getAttribute('data-name');
            const skillPercentage = this.getAttribute('data-percentage');
            const skillCategory = this.getAttribute('data-category');
            const skillIcon = this.getAttribute('data-icon');

            // Hide add form and show edit form
            addSkillForm.style.display = 'none';
            editSkillForm.style.display = 'block';

            // Fill in the edit form
            document.getElementById('edit_skill_id').value = skillId;
            document.getElementById('edit_skill_name').value = skillName;
            document.getElementById('edit_percentage').value = skillPercentage;
            document.getElementById('edit_category').value = skillCategory;
            document.getElementById('edit_icon_class').value = skillIcon;

            // Update icon preview
            editIconPreview.innerHTML = `<i class="${skillIcon}"></i>`;

            // Scroll to edit form
            editSkillForm.scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Delete confirmation
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });
});
EOT;

// Write the content to a temporary file
file_put_contents('e:/Folder1/htdocs/portfolio/js/admin.js', $temp_content);
