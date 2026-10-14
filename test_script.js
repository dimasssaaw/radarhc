const document = {getElementById: () => ({getContext: () => ({})}), body: {addEventListener: () => {}}}; const window = {}; const Chart = function(){}; 

    function renderIdpChart() {
        const ctxEl = document.getElementById('competencyChart');
        if (!ctxEl) return;
        
        const jabatan = "head";
        const penilaianStr = null;
        let penilaian = null;
        try {
            if (penilaianStr) penilaian = JSON.parse(penilaianStr);
        } catch(e) {
            penilaian = null;
        }
        
        let labels = [];
        let dataScores = [];
        let cardsHtml = '';
        let total = 0;
        let count = 0;

        if (jabatan === 'head') {
            const keys = ['Managing Change', 'Empowerment', 'Fostering Teamwork', 'Analysis & Decision Making', 'Planning & Controlling', 'Integrity & Ethics', 'Excellence', 'Compassion', 'Humility'];
            const dbKeys = ['head_managing_change', 'head_empowerment', 'head_fostering_teamwork', 'head_analysis_decision', 'head_planning_controlling', 'head_integrity_ethics', 'head_excellence', 'head_compassion', 'head_humility'];
            
            keys.forEach((key, index) => {
                labels.push(key);
                let score = penilaian ? (penilaian[dbKeys[index]] || 0) : 0;
                dataScores.push(score);
                total += parseFloat(score);
                count++;
                
                cardsHtml += '<div class="bg-white rounded-lg p-4 border border-emerald-100 shadow-sm"><h5 class="text-sm font-bold text-gray-900 mb-1">' + (index+1) + '. ' + key + '</h5><p class="text-xs text-gray-500 font-medium">Skor: ' + parseFloat(score).toFixed(1) + '</p></div>';
            });
        } else if (jabatan === 'staff') {
            const keys = ['Leads Courageously', 'Coach & Develop Others', 'Teamwork', 'Trouble Shooting', 'Customer Satisfaction', 'Cross Functional Capability', 'Quality Focus', 'Continuous Improvement', 'Planning Monitoring', 'Personal Integrity', 'Drive For Result', 'Learning'];
            const dbKeys = ['staff_leads_courageously', 'staff_coach_develop', 'staff_teamwork', 'staff_trouble_shooting', 'staff_customer_satisfaction', 'staff_cross_functional', 'staff_quality_focus', 'staff_continuous_improvement', 'staff_planning_monitoring', 'staff_personal_integrity', 'staff_drive_for_result', 'staff_learning'];
            
            keys.forEach((key, index) => {
                labels.push(key);
                let score = penilaian ? (penilaian[dbKeys[index]] || 0) : 0;
                dataScores.push(score);
                total += parseFloat(score);
                count++;
                
                cardsHtml += '<div class="bg-white rounded-lg p-4 border border-emerald-100 shadow-sm"><h5 class="text-sm font-bold text-gray-900 mb-1">' + (index+1) + '. ' + key + '</h5><p class="text-xs text-gray-500 font-medium">Skor: ' + parseFloat(score).toFixed(1) + '</p></div>';
            });
        } else {
            const keys = ['Directiveness', 'Monitoring', 'Teamwork', 'Quality & Customer Focus', 'Continuous Improvement', 'Personal Integrity'];
            const dbKeys = ['unit_directiveness', 'unit_monitoring', 'unit_teamwork', 'unit_quality_customer', 'unit_continuous_improvement', 'unit_personal_integrity'];
            
            keys.forEach((key, index) => {
                labels.push(key);
                let score = penilaian ? (penilaian[dbKeys[index]] || 0) : 0;
                dataScores.push(score);
                total += parseFloat(score);
                count++;
                
                cardsHtml += '<div class="bg-white rounded-lg p-4 border border-emerald-100 shadow-sm"><h5 class="text-sm font-bold text-gray-900 mb-1">' + (index+1) + '. ' + key + '</h5><p class="text-xs text-gray-500 font-medium">Skor: ' + parseFloat(score).toFixed(1) + '</p></div>';
            });
        }

        const cardsContainer = document.getElementById('competencyCards');
        if(cardsContainer) {
            cardsContainer.innerHTML = cardsHtml;
        }
        
        const avgContainer = document.getElementById('avgScore');
        if(avgContainer && count > 0) {
            avgContainer.innerText = 'Avg: ' + (total/count).toFixed(2) + ' / 5.0';
        }

        if (window.idpChartInstance) {
            window.idpChartInstance.destroy();
        }

        const ctx = ctxEl.getContext('2d');
        window.idpChartInstance = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Max Score',
                        data: Array(labels.length).fill(5),
                        backgroundColor: 'transparent',
                        borderColor: '#d97706',
                        borderWidth: 1.5,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        pointHoverRadius: 0,
                        fill: false
                    },
                    {
                        label: 'Skor',
                        data: dataScores,
                        backgroundColor: 'rgba(16, 185, 129, 0.25)', 
                        borderColor: '#0ea5e9',
                        pointBackgroundColor: '#0ea5e9',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#0ea5e9',
                        borderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        angleLines: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            lineWidth: 1
                        },
                        grid: {
                            color: 'transparent',
                            circular: false,
                        },
                        pointLabels: {
                            display: true,
                            color: '#1f2937',
                            font: {
                                size: 10,
                                weight: '600',
                                family: "'Inter', sans-serif"
                            },
                            padding: 20
                        },
                        ticks: {
                            beginAtZero: true,
                            min: 0,
                            max: 5,
                            stepSize: 1,
                            display: false, 
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                        titleFont: { size: 13, family: "'Inter', sans-serif" },
                        bodyFont: { size: 14, weight: 'bold', family: "'Inter', sans-serif" },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false
                    }
                }
            }
        });
    }

    // Eksekusi segera
    setTimeout(renderIdpChart, 50);

    // Untuk HTMX SPA swap
    document.body.addEventListener('htmx:afterSwap', function(event) {
        if (event.detail.target.id === 'competencyChart' || document.getElementById('competencyChart')) {
            setTimeout(renderIdpChart, 50);
        }
    });


renderIdpChart();
