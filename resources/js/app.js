import Alpine from 'alpinejs';
import { useAuthAnimations, useToast, useConfirm, useTabs, useTaskDetails, useCountdown } from './hooks';
import { useTaskForm, initAssignments, initLoginForm } from './components';

window.Alpine = Alpine;
window.useToast = useToast;
window.useConfirm = useConfirm;
window.useTaskDetails = useTaskDetails;
window.useCountdown = useCountdown;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    useAuthAnimations().init();
    useTabs('class-tabs', 'class-tab-panels');
    useTaskForm('create-task-form');
    initAssignments();
    initLoginForm();
});
