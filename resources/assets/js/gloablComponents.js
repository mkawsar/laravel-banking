import DropDown from './components/UIComponents/Dropdown';
import Radio from './components/UIComponents/Inputs/Radio';

const GlobalComponents = {
    install (Vue) {
        Vue.component(DropDown.name, DropDown);
    }
};

export default GlobalComponents;
