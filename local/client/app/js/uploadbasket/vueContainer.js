export class VueContainer {
  static modules = {};

  static add(alias, module) {
    VueContainer.modules[alias] = module;
  }

  static get(alias) {
    return VueContainer.modules[alias];
  }
}

window.VueContainer = VueContainer;