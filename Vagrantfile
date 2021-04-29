# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure("2") do |config|
  
  config.vm.define "gateway" do |gateway|
    gateway.vm.box = "damilolarandolph/rusty-ubuntu"

    gateway.vm.network "private_network", ip: "192.168.33.10"
    gateway.vm.synced_folder "./covpay", "/home/benson/covpay", type: "nfs", nfs_udp:false
    gateway.vm.synced_folder "./common", "/home/benson/common", type: "nfs", nfs_udp: false

  end

config.vm.define "school" do |school|
    school.vm.box = "damilolarandolph/rusty-ubuntu"

    school.vm.network "private_network", ip: "192.168.33.11"
    school.vm.synced_folder "./school", "/home/benson/school", type: "nfs", nfs_udp:false
    school.vm.synced_folder "./common", "/home/benson/common", type: "nfs", nfs_udp: false

  end

config.vm.define "mastercard" do |mastercard|
    mastercard.vm.box = "damilolarandolph/rusty-ubuntu"

    mastercard.vm.network "private_network", ip: "192.168.33.12"
    mastercard.vm.synced_folder "./mastercard", "/home/benson/mastercard", type: "nfs", nfs_udp:false
    mastercard.vm.synced_folder "./common", "/home/benson/common", type: "nfs", nfs_udp: false

  end

config.vm.define "banknet" do |banknet|
    banknet.vm.box = "damilolarandolph/rusty-ubuntu"

    banknet.vm.network "private_network", ip: "192.168.33.13"
    banknet.vm.synced_folder "./banknet", "/home/benson/banknet", type: "nfs", nfs_udp:false
    banknet.vm.synced_folder "./common", "/home/benson/common", type: "nfs", nfs_udp: false

  end

config.vm.define "zenith" do |zenith|
    zenith.vm.box = "damilolarandolph/rusty-ubuntu"

    zenith.vm.network "private_network", ip: "192.168.33.14"
    zenith.vm.synced_folder "./zenith", "/home/benson/zenith", type: "nfs", nfs_udp:false
    zenith.vm.synced_folder "./common", "/home/benson/common", type: "nfs", nfs_udp: false

  end




    

  end
