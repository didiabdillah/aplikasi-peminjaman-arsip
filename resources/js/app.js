import './bootstrap';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import $ from 'jquery';

// Make jQuery available globally
window.$ = window.jQuery = $;

import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// jQuery ready function
$(document).ready(function() {
    // Initialize DataTables for tables with class 'data-table'
    if ($.fn.DataTable) {
        $('.data-table').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            }
        });
    }

    // AJAX setup for CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Archive search functionality
    $('#archive-search').on('input', function() {
        var query = $(this).val();
        if (query.length > 2) {
            searchArchives(query);
        } else {
            $('#search-results').empty().hide();
        }
    });

    function searchArchives(query) {
        $.ajax({
            url: '/archives/search/ajax',
            method: 'GET',
            data: { q: query },
            success: function(data) {
                var results = $('#search-results');
                results.empty();
                if (data.length > 0) {
                    data.forEach(function(archive) {
                        results.append(
                            '<div class="search-result-item p-2 borderbottom" data-id="' + archive.id + '">' +
                            '<strong>' + archive.code + '</strong> - ' +
                            archive.title +
                            '<br><small class="text-muted">' +
                            archive.location + '</small>' +
                            '</div>'
                        );
                    });
                    results.show();
                } else {
                    results.append('<div class="p-2 text-muted">Tidak ada arsip ditemukan</div>').show();
                }
            },
            error: function() {
                console.error('Error searching archives');
            }
        });
    }

    // Handle search result selection
    $(document).on('click', '.search-result-item', function() {
        var archiveId = $(this).data('id');
        var archiveText = $(this).text();
        $('#archive_id').val(archiveId);
        $('#archive-search').val(archiveText);
        $('#search-results').hide();
    });

    // Hide search results when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#archive-search, #search-results').length)
        {
            $('#search-results').hide();
        }
    });
});

// Custom JavaScript untuk aplikasi
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[databs-toggle="tooltip"]'));

    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[databs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert:not(.alertpermanent)');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Confirm delete actions
    document.querySelectorAll('.btn-delete').forEach(function(button) {
        button.addEventListener('click', function(e) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                e.preventDefault();
            }
        });
    });
});